<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Validator;
use App\Services\LinkService;

class LinkController extends Controller
{

    protected $_validator;
    protected $request;
    protected $linkService;

    public function __construct(Validator $validator, Request $request, LinkService $linkService)
    {
        $this->_validator = $validator;
        $this->request = $request;
        $this->linkService = $linkService;
    }

    /**
     * @SWG\Get(
     *     path="links",
     *     summary="Get the menu links list",
     *     description="To get the menu links list",
     *     produces= {"application/json"},
     *     tags={"Menu Links"},
     *     @SWG\Response(
     *         response=200,
     *         description="links list",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="No data available",
     *     )
     *      
     *      
     * )
     */
    public function index()
    {
        $details = $this->linkService->getLinks();
        return $this->success($details);
    }

}
