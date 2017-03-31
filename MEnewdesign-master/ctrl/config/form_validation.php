<?php

/* setting validation group */

$config = array(
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
                                         )
                                    
                                    )
                           
               );

