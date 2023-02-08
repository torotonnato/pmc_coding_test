<?php

namespace app\models\validators;

use Intlchar;
use Yii;
use yii\validators\Validator;

class PasswordStrength extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if ($attribute != 'password') {
            return;
        }
        if (!self::isStrong($model->$attribute)) {
            $params = Yii::$app->params['password']['strength'];
            $msg = "The password must be at least {$params['minLen']} " . 
                   "characters long and contain at least " .
                   "{$params['minLowerCaseCPs']} lower case char(s), " . 
                   "{$params['minUpperCaseCPs']} upper case char(s), " .
                   "{$params['minDigits']} digit(s) " . 
                   "and {$params['minPuncts']} punctuation mark(s)";
            $this->addError($model, $attribute, $msg);
        }
    }
    /**
     * Checks if the provided password is secure.
     *
     * @param string $password the password currently being checked
     */
    public static function isStrong($password)
    {
        if (!$password) {
            return null;
        }

        $pwdStrength = Yii::$app->params['password']['strength'];
        if (!$pwdStrength['enable'])
            return true;

        if (strlen($password) < $pwdStrength['minLen'])
            return false;

        $stats = [
            'digits' => 0,
            'lowers' => 0,
            'uppers' => 0, 
            'blanks' => 0,
            'puncts' => 0
        ];
        
        $stats = array_reduce(
            mb_str_split($password),
            function ($stats, $ch)
            {
                $stats['digits'] += IntlChar::isdigit($ch);
                $stats['lowers'] += IntlChar::islower($ch);
                $stats['uppers'] += IntlChar::isupper($ch);
                $stats['blanks'] += IntlChar::isblank($ch);
                $stats['puncts'] += IntlChar::ispunct($ch);
                return $stats;
            },
            $stats
        );  

        $valid = ($stats['lowers'] >= $pwdStrength['minLowerCaseCPs']) &
                ($stats['uppers'] >= $pwdStrength['minUpperCaseCPs']) &
                ($stats['digits'] >= $pwdStrength['minDigits']) &
                ($stats['blanks'] == 0) &
                ($stats['puncts'] >= $pwdStrength['minPuncts']);

        return $valid;
    }
}

?>