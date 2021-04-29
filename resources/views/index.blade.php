<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Hacker News</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="{{ asset('css/style.css')}}" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    </head>
    <body>
    	<div class="container">
    		
    		<table width="100%">
    			<tr>
    				<td class="head">
			    		<table>
			    			<tr>
			    				<td class="logo"><img src="{{asset('images/y18.gif')}}"></td>
			    				<td class="pagetop">
			    					<span>
			    						<b class="hackername"><a href="{{url('news')}}">Hacker News</a></b>
					    				<a href="#">news</a>
					    				<a href="#">past</a>
					    				<a href="#">comments</a>
					    				<a href="#">ask</a>
					    				<a href="#">show</a>
					    				<a href="#">jobs</a>
					    				<a href="#">submit</a>
			    					</span>
			    				</td>
			    				<td class="login">
			    					<span><a href="#">login</a></span>
			    				</td>
			    			</tr>
			    		</table>
			    	</td>
			    </tr>

			    <tr style="background-color: #F6F6EF">
			    	<td id="pagespace"></td>
			    </tr>
			    <?php $count = 0; ?>
			    <tr class="item">
			    	<td>
			    		<table class="itemList">
			    		
			    			@foreach($getStories as $story)
			    			<?php 
			    				$count++; 
			    				
			    				$parts = parse_url($story->url); 
			    			?>
			    				<tr class="text">
			    					<td align="right" valign="top" width="0"><span>{{$count}}.</span></td>
			    					<td>{{$story->title}} <span class="siteUrl"><a href="#">(<?php echo isset($parts["host"]) ? $parts["host"]: null; ?>)</a></span></td>
			    				</tr>
			    				<tr class="subtext">
			    					<td colspan="2"><span>{{$story->score}} points by {{$story->by}} {{$story->created_at->diffForHumans()}} | <a href="#">{{count($story->comments)}} comments </a></span></td>
			    				</tr>
			    			@endforeach
			    		</table>
			    	</td>
			    </tr>
			</table>
			<span>
				{{$getStories->links()}}
			</span>
    	</div>
    	<script src="{{asset('js/app.js')}}"></script>
    </body>