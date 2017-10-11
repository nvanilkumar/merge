<?php

namespace App\Services;

use Illuminate\Validation\Factory as IlluminateValidator;
use Illuminate\Http\Request;
use App\Exceptions\InvalidArgumentException;
use App\Models\CommonModel;

class Validator
{

    protected $_validator;
    protected $request;

    public function __construct(IlluminateValidator $validator, Request $request)
    {
        $this->_validator = $validator;
        $this->request = $request;
        $this->commonModel = new CommonModel();
    }

    public function validate($validation_type)
    {
        $this->$validation_type();
    }

    /**
     * To check the user login validations
     * @return boolean
     */
    public function loginValidations()
    {
        $login_rules = ['username', 'password'];
        $this->expectValueAll($login_rules);
        return true;
    }

    /**
     * To check the device token validations
     * @return boolean
     */
    public function deviceTokenValidations()
    {

        $property_array = [
            'device_token_id',
        ];

        $this->expectValueAll($property_array);
        $this->checkValues('device_type');
        $this->expectInt('user_id');
        
        return true;
    }

    /**
     * To check the forgot password validations
     * @return boolean
     */
    public function forgotPasswordValidations()
    {
        $value = $this->expectValue('user_name');

        return true;
    }

    /**
     * To check the change password validations
     * @return boolean
     */
    public function changePasswordValidations()
    {
        $value = $this->expectInt('user_id');

        $property_array = [
            'old_password',
            'new_password',
        ];
        $this->expectValueAll($property_array);
        return true;
    }

    /**
     * To check the event list validations
     * @return boolean
     */
    public function eventListValidations()
    {
        $value = $this->expectInt('user_id');
        return true;
    }

    /**
     * To check the event related user status validations
     * @return boolean
     */
    public function eventUserStatusValidations()
    {
        $this->expectInt('user_id');
        $this->expectInt('event_id');
        $this->checkValues('status');

        return true;
    }

    /**
     * To check the create event validations
     * @return boolean
     */
    public function createEventValidations()
    {
        $property_array = [
            'event_name',
            'event_description',
        ];
        $this->expectValueAll($property_array);
        $this->expectInt('user_id');

        $dateProperty_array = [
            'event_start_date',
            'event_end_date'
        ];
        $this->expectDateAll($dateProperty_array);

        $st_date = $this->request->input('event_start_date');
        $en_date2 = $this->request->input('event_end_date');
        $this->dateCompare($st_date, $en_date2);

        return true;
    }

    /**
     * To check the event related user status validations
     * @return boolean
     */
    public function surveyUserStatusValidations()
    {
        $this->expectInt('user_id');
        $this->expectInt('survey_id');
        $this->checkValues('status');

        return true;
    }

    /**
     * To check the topic list validations
     * @return boolean
     */
    public function topicListValidations()
    {
        if (!($this->request->input('topic_name')) && !($this->request->input('category_id'))) {
            throw new InvalidArgumentException("missing either category_id or topic_name", 10055);
        }

        if ($this->request->input('category_id')) {
            $this->expectInt('category_id');
        }
        return true;
        
    }
    
    /**
     * To check the create topic validations
     * @return boolean
     */
    public function createTopicValidations()
    {
        $property_array = [
            'topic_name',
            'topic_description'
        ];
        $this->expectValueAll($property_array);
        $this->expectInt('category_id');
        $this->expectInt('user_id');


        return true;
        
    }
    
    /**
     * To check the create topic validations
     * @return boolean
     */
    public function createCommentValidations()
    {
        $property_array = [ 'comment_description'];
        $this->expectValueAll($property_array);
        $this->expectInt('topic_id');
        $this->expectInt('user_id');


        return true;
        
    }
    
    /**
     * To check topic validations
     * @return boolean
     */
    public function getTopicValidations()
    {
        $this->expectInt('topic_id');
        return true;
        
    }

    public function expectValueAll($property_array)
    {
        foreach ($property_array as $property) {
            if (!($this->request->input($property))) {
                throw new InvalidArgumentException("missing " . $property . " property", 10055);
            }
            $this->expectValue($property);
        }
        return true;
    }

    /**
     * To check the Integer value of the property
     * @param type $property
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function expectInt($property)
    {
        $value = $this->expectValue($property);
        if (!is_numeric($this->request->input($property))) {
            throw new InvalidArgumentException('Invalid type for ' . $property . '. Expected integer, got: ' . gettype($value), 10054);
        }
        return TRUE;
    }

    /**
     * To check the property exist in given request
     * @param type $property
     * @return type
     * @throws InvalidArgumentException
     */
    public function expectValue($property)
    {
        $value = $this->request->input($property);
        if ($value === null) {
            throw new InvalidArgumentException('Must provide property ' . $property, 10056);
        }

        return $value;
    }

    /**
     * To check the accepted values list
     * @param type $property
     * @return type
     * @throws InvalidArgumentException
     */
    public function checkValues($property)
    {

        $statusValues = $this->getLookupValues();

        $value = $this->request->input($property);
        if ($value === null) {
            throw new InvalidArgumentException('Must provide property ' . $property, 10056);
        }

        $valueCheck = FALSE;
        foreach ($statusValues as $status) {
            if ($status->status_name == $value) {
                $valueCheck = TRUE;
                $this->request->request->add(['status_id'=> $status->status_id]);
                break;
            }
        }
        if (!$valueCheck) {
            throw new InvalidArgumentException('Invalid  ' . $property . " property value : " . $value, 10057);
        }
        return $value;
    }

    /**
     * To loop the all date fileds
     * @param type $property_array
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function expectDateAll($property_array)
    {
        foreach ($property_array as $property) {
            if (!($this->request->input($property))) {
                throw new InvalidArgumentException("missing " . $property . " property", 10055);
            }
            $this->dateValidation($this->request->input($property), $property);
        }
        return true;
    }

    /**
     * To validate the date value with format YYY-MM-DD
     * @param type $date
     * @param type $propertyName
     * @param type $separator
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function dateValidation($date, $propertyName, $separator = '-')
    {
        $parts = explode($separator, $date);
        if (!(count($parts) == 3)) {
            throw new InvalidArgumentException('Invalid date format for ' . $propertyName . '. Expected Date format yyyy-mm-dd', 10053);
        }
        //Pass Month Date Year
        if (checkdate($parts[1], $parts[2], $parts[0])) {
            return true;
        }

        throw new InvalidArgumentException('Invalid date format for ' . $propertyName . '. Expected Date format yyyy-mm-dd', 10054);
    }

    /**
     * To compare the start date & end date values
     * @param type $st_date
     * @param type $en_date2
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function dateCompare($st_date, $en_date2)
    {
        $start_date = strtotime($st_date);
        $end_date = strtotime($en_date2);

        if ($start_date > $end_date) {
            throw new InvalidArgumentException('end date should be greater than start date', 10054);
        }
        return true;
    }

    /**
     * To Bring the lookup values
     */
    public function getLookupValues()
    {
        $this->commonModel->setTableName('statuslookup');
        $records = $this->commonModel->getData();
        return $records;
    }

}
