<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comments;

class ItemController extends Controller
{
	private $baseUrl = '';
	private $storyUrl = '';
	private $commentIds = [];


	public function __construct()
	{
		// $this->middleware('auth', ['except' => ['index', 'show']]); 

		$this->baseUrl = 'https://hacker-news.firebaseio.com/v0/';
		$this->storyUrl = $this->baseUrl;
		$this->storyUrl .= 'item/';
	}

    public function index()
    {
    	$getStories = Item::where('deleted', null)->orderByDesc('time','score')->with('comments')->take(500)->paginate(30);
 	
    	return view('index', compact('getStories'));
    }

    public function getStoryIds()
    {
    	$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->baseUrl . 'topstories.json',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);

		curl_close($curl);

		if($response)
		{
			$getIds = json_decode($response);

			// Get All stored item news ids
    		$storedStories = Item::all();
    		
    		if(count($storedStories) > 0)
    		{
	    		foreach ($storedStories as $storedStory) {
	    			$storedStoryIds[] = $storedStory->id;
	    		}

	    		// compare the latest item news ids with the stored item news ids
	    		return $newStoryIds = array_diff($getIds, $storedStoryIds);
    		}
    		return $getIds;
		}
    }

    public function getItems()
    {
    	$storyIds = isset($_POST["ids"]) ? $_POST["ids"] : null;
    	
    	if(is_array($storyIds) && count($storyIds) > 0)
    	{
    		foreach ($storyIds as $storyId) {

    			$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => $this->storyUrl.$storyId.'.json',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'GET',
				));

				$response = curl_exec($curl);

				curl_close($curl);

				$stories = json_decode($response);
				$this->storeNewsItem($stories);

				// this also needs to be recursive
				if(isset($stories->kids) && is_array($stories->kids) && count($stories->kids) > 0)
				{
					$this->storeComments($stories->kids);
				}
    		}
    	}
    }

    public function storeNewsItem($story)
    {	
    	if(isset($story->kids))
    	{
    		$this->commentIds[] = $story->kids;
    	}	

		$newItem = new Item;
		$newItem->id = $story->id;
		$newItem->type = $story->type;
		$newItem->by = $story->by;
		$newItem->time = $story->time;
		$newItem->url = isset($story->url) ? $story->url : null;
		$newItem->title = $story->title;
		$newItem->score = $story->score;

		$newItem->save();
    }

    public function storeComments($kidIds)
    {
    
		foreach ($kidIds as $kidId) {

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->storyUrl.$kidId.'.json',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'GET',
			));

			$response = curl_exec($curl);

			curl_close($curl);

			$story = json_decode($response);
			
			$newComment = new Comments;
			$newComment->id = $story->id;
			$newComment->item_id = $story->parent;
			$newComment->body = isset($story->text) ? $story->text : null;

			$newComment->save();
		}
    	
    }
}
