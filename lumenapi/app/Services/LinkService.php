<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\LinksModel;

class LinkService
{

    protected $request;
    protected $linksModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->linksModel = new LinksModel();
    }

    /**
     * To get the links list
     */
    public function getLinks()
    {
        $links = $this->linksModel->getLinks();
        if (count($links) == 0) {
            $responseMessage["statusCode"] = STATUS_NO_DATA_FOUND;
            $responseMessage["response"]['status'] = FALSE;
            $responseMessage["response"]['message'] = config('mts-config.links.zerorecords');
            return $responseMessage;
        }

        $responseMessage["statusCode"] = STATUS_OK;
        $responseMessage["response"]['status'] = true;
        $responseMessage["response"]['links'] = $links;
        return $responseMessage;
    }

}
