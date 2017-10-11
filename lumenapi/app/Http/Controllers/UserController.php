<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Validator;
use App\Services\UserService;

class UserController extends Controller
{

    protected $_validator;
    protected $request;
    protected $userService;

    public function __construct(Validator $validator, Request $request, UserService $userService)
    {
        $this->_validator = $validator;
        $this->request = $request;
        $this->userService = $userService;
    }

    /**
     * @SWG\Put(
     *     path="devicetokens/create",
     *     summary="To insert the user device token",
     *     description="To insert the user specific active device token",
     *     produces= {"application/json"},
     *     tags={"User"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Device token Object",
     *         required=true,
     *          @SWG\Schema(
     *              required={"device_token_id","device_type", 	"user_id"},
     *              @SWG\Property(property="device_token_id",  type="string",  ),
     *              @SWG\Property(property="device_type",  type="string",  ),
     *              @SWG\Property(property="user_id",  type="integer",  ),
     *          ),
     *      ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successfully inserted",
     *        
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="missing property name",
     *     )
     *      
     *      
     * )
     */
    public function deviceTokenCreate()
    {

        $status = $this->_validator->validate("deviceTokenValidations");
        $details = $this->userService->deviceTokenCreate();

        return $this->success($details);
    }

    public function usersCreate()
    {
        $details = $this->userService->createUser();

        return $this->success($details);
    }

    /**
     * @SWG\Post(
     *     path="login",
     *     summary="Login Check",
     *     description="To check User login related details",
     *     produces= {"application/json"},
     *     tags={"Login"},
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="username of the user",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="login password",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successfully logged in",
     *        
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Invalid login details",
     *     )
     *      
     *      
     * )
     */
    public function loginCheck()
    {

        $status = $this->_validator->validate("loginValidations");
        $details = $this->userService->loginCheck();

        return $this->success($details);
    }

    /**
     * @SWG\Post(
     *     path="users/forgotpassword",
     *     summary="Forgot Password",
     *     description="To send the password reset link to user email",
     *     produces= {"application/json"},
     *     tags={"Login"},
     *     @SWG\Parameter(
     *         name="user_name",
     *         in="formData",
     *         description="Login user id",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successfully sent the email",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Invalid user name details",
     *     )
     *      
     *      
     * )
     */
    public function forgotPassword()
    {
        $status = $this->_validator->validate("forgotPasswordValidations");
        $details = $this->userService->forgotPassword();

        return $this->success($details);
    }

    /**
     * @SWG\Post(
     *     path="users/changepassword",
     *     summary="To change the user Password",
     *     description="To change the user login password",
     *     produces= {"application/json"},
     *     tags={"Login"},
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="formData",
     *         description="login user id",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Parameter(
     *         name="old_password",
     *         in="formData",
     *         description="Old password",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Parameter(
     *         name="new_password",
     *         in="formData",
     *         description="New password",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successfully sent the email",
     *        
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Invalid user id details",
     *     )
     *      
     *      
     * )
     */
    public function changePassword()
    {
        $status = $this->_validator->validate("changePasswordValidations");
        $details = $this->userService->changePassword();

        return $this->success($details);
    }
    
     /**
     * @SWG\Get(
     *     path="chat/users",
     *     summary="Get the chat rooms list",
     *     description="To get the chat rooms list",
     *     produces= {"application/json"},
     *     tags={"User"},
     *     @SWG\Response(
     *         response=200,
     *         description="Chat users related information",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="No data available",
     *     )
     * )
     */
    public function chatUsers()
    {
        $details = $this->userService->chatUsers();
        return $this->success($details);
    }        
 
     /**
     * @SWG\Get(
     *     path="dashboard/{user_id}",
     *     summary="Dashboard details",
     *     description="Get the user related dashboard details",
     *     produces= {"application/json"},
     *     tags={"User"},
     *     @SWG\Parameter(
     *         description="user_id",
     *         format="int64",
     *         in="path",
     *         name="user_id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Dashboard details",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="No data available",
     *     )
     * )
     */
    public function dashboard($user_id)
    {
        $this->request->request->add(['user_id'=> $user_id]);
        $this->_validator->validate("dashboardValidations");
        
        $details = $this->userService->dashboard();
        return $this->success($details);
    }        
}
