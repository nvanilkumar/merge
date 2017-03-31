<?php

/* setting validation group */

$config = array(
    'addDiscount' => array(
        array(
            'field' => 'discountName',
            'label' => 'Discount Name',
            'rules' => 'required_strict|max_length[50]'
        ),
        array(
            'field' => 'discountCode',
            'label' => 'Discount Code',
            'rules' => 'required_strict|max_length[50]'
        ),
        array(
            'field' => 'eventId',
            'label' => 'Event Id',
            'rules' => 'is_natural_no_zero|required_strict'
        ),
        array(
            'field' => 'discountStartDate',
            'label' => 'Discount Start Date',
            'rules' => 'required_strict|specialDate'
        ),
        array(
            'field' => 'discountStartTime',
            'label' => 'Discount Start Time',
            'rules' => 'required_strict|specialTime'
        ),
        array(
            'field' => 'discountEndDate',
            'label' => 'Discount End Date',
            'rules' => 'required_strict|specialDate'
        ),
        array(
            'field' => 'discountEndTime',
            'label' => 'Discount End Time',
            'rules' => 'is_natural_no_zero|specialTime'
        ),
        array(
            'field' => 'discountValue',
            'label' => 'Discount Value',
            'rules' => 'required_strict|numeric'
        ),
        array(
            'field' => 'amountType',
            'label' => 'Discount Type',
            'rules' => 'required_strict|type'
        ),
        array(
            'field' => 'usageLimit',
            'label' => 'Usage Limit',
            'rules' => 'numeric|required_strict'
        ),
        array(
            'field' => 'type',
            'label' => 'Discount Type',
            'rules' => 'required_strict'
        )
    ),
    'signup' => array(
        array(
            'field' => 'name',
            'label' => 'Name',
            'rules' => 'required_strict|name|max_length[50]'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required_strict|valid_email'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required_strict|min_length[6]'
        ),
        array(
            'field' => 'phonenumber',
            'label' => 'Phone Number',
            'rules' => 'required_strict|numeric|min_length[10]|max_length[20]'
        ),
        array(
            'field' => 'deviceId',
            'label' => 'Device Id',
            'rules' => 'alphanumeric|min_length[1]|max_length[60]'
        ),
        array(
            'field' => 'deviceType',
            'label' => 'Device Type',
            'rules' => 'specialname|min_length[1]|max_length[50]'
        )
    ),
    'eventId' => array(
        array(
            'field' => 'eventId',
            'label' => 'EventId',
            'rules' => 'is_natural_no_zero|required_strict'
        )
    ),
    'promoter' => array(
        array(
            'field' => 'name',
            'label' => 'Name',
            'rules' => 'required_strict|name|max_length[50]'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required_strict|valid_email'
        ),
        array(
            'field' => 'code',
            'label' => 'Code',
            'rules' => 'required_strict'
        )
    ),
    'collaborator' => array(
        array(
            'field' => 'eventid',
            'label' => 'eventid',
            'rules' => 'required_strict|is_natural_no_zero'
        ),
        array(
            'field' => 'name',
            'label' => 'Name',
            'rules' => 'required_strict|namePattern|max_length[50]'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required_strict|valid_email'
        ),
        array(
            'field' => 'mobile',
            'label' => 'Mobile Number',
            'rules' => 'phone'
        ),
        array(
            'field' => 'promote',
            'label' => 'promote',
            'rules' => 'enable'
        ),
        array(
            'field' => 'manage',
            'label' => 'manage',
            'rules' => 'enable'
        ),
        array(
            'field' => 'report',
            'label' => 'report',
            'rules' => 'enable'
        )
    ),
    'adduser' => array(
        array(
            'field' => 'name',
            'label' => 'Name',
            'rules' => 'required_strict|name|max_length[50]'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required_strict|valid_email'
        ),
        array(
            'field' => 'mobile',
            'label' => 'Mobile',
            'rules' => 'required_strict|numeric|min_length[10]|max_length[20]'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required_strict|min_length[6]'
        )
    ),
    'sendcollaboratormail' => array(
        array(
            'field' => 'eventid',
            'label' => 'EventId',
            'rules' => 'is_natural_no_zero|required_strict'
        ),
        array(
            'field' => 'email',
            'label' => 'email',
            'rules' => 'required_strict|valid_email'
        )
    ),
    'bankDetails' => array(
        array(
            'field' => 'accountName',
            'label' => 'Account Name',
            'rules' => 'required_strict|accountholdername|max_length[50]'
        ),
        array(
            'field' => 'accountNumber',
            'label' => 'Account Number',
            'rules' => 'required_strict|is_natural|max_length[30]'
        ),
        array(
            'field' => 'bankName',
            'label' => 'Bank Name',
            'rules' => 'required_strict|bankname'
        ),
        array(
            'field' => 'branch',
            'label' => 'branch',
            'rules' => 'required_strict'
        ),
        array(
            'field' => 'ifsccode',
            'label' => 'Ifsc Code',
            'rules' => 'required_strict|alpha_numeric'
        )
    ),
    'OfflinePromoter' => array(
        array(
            'field' => 'promoterName',
            'label' => 'Promoter Name',
            'rules' => 'required_strict'
        ),
        array(
            'field' => 'promoterEmail',
            'label' => 'Promoter Email',
            'rules' => 'required_strict|valid_email'
        ),
        array(
            'field' => 'userId',
            'label' => 'User Id',
            'rules' => 'required_strict|is_natural_no_zero'
        ),
        array(
            'field' => 'eventId',
            'label' => 'EventId',
            'rules' => 'is_natural_no_zero|required_strict'
        ),
        array(
            'field' => 'mobile',
            'label' => 'Mobile',
            'rules' => 'required_strict|numeric|min_length[10]|max_length[10]'
        )
//        ,
//        array(
//            'field' => 'startTime',
//            'label' => 'Discount Start Time',
//            'rules' => 'required_strict|specialTime'
//        )
    ),
    'addsessionlock'=>array(
        array(
            'field' => 'orderId',
            'label' => 'order id',
            'rules' => 'required_strict|alpha_numeric'
        ),
        array(
            'field' => 'ticketArray',
            'label' => 'ticketArray',
            'rules' => 'required_strict|is_array'
        )
    ),
    'getsessionlock'=>array(
        array(
            'field' => 'ticketIds',
            'label' => 'ticketIds',
            'rules' => 'required_strict|is_array'
        )
    )
);

