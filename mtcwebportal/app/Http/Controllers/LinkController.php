<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LinkService;
use Session;

class LinkController extends Controller
{

    public function __construct(Request $request, LinkService $linkService)
    {
        $this->request = $request;
        $this->linkService = $linkService;
    }

    public function creatLinkView()
    {
        return view('links.links', ["title" => "Create Link",
            "link" => ""]);
    }

    public function updateLinkView($link_id)
    {
        $this->request->request->add(['link_id' => $link_id]);
        $linkDetails = $this->linkService->getLinkDetails();
        if ($linkDetails == NULL) {
            return redirect('/dashboard');
        }
        $link = (array) $linkDetails[0];

        return view('links.links', ["title" => "Update Link",
            "link" => $link]);
    }

    public function creatLink()
    {
        $this->linkService->createLink();
        return redirect('/links/list');
    }

    /**
     * To update the posted link data
     * @return type
     */
    public function updateLink()
    {

        $this->linkService->updateLink();
        return redirect('/links/update/' . $this->request->input("link_id"))
                ->with('status','Link updated successfully');
    }

    public function getLinkList()
    {
        $lists = $this->linkService->getLinkList();
        return view('links.list', ["title" => "All Links",
            "linkList" => $lists]);
    }

    public function updateMenuPositon()
    {
        $status = $this->linkService->updateMenuPositon();
        return $status;
    }

    /**
     * To delete the specified link id
     * @return type
     */
    public function deleteLink($link_id)
    {
        $this->request->request->add(['link_id' => $link_id]);
        $this->linkService->deleteLink();
        Session::flash('status', 'Success! Record is deleted successfully.');
        return redirect('/links/list');
    }

}
