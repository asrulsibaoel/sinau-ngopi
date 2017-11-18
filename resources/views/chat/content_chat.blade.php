@if(count($data)>0)
@foreach ($data as $chat)

			<div class="direct-chat-msg
	@if($chat->user->id==$users_id)
		right
	@endif
			">
	          <div class="direct-chat-info clearfix">
	            <span class="direct-chat-name pull-left">{{$chat->user->name}} </span>
	            <span class="direct-chat-timestamp pull-right">{{$chat->created_at}}</span>
	          </div>
	          <!-- /.direct-chat-info -->
	          <img class="direct-chat-img" src="{{$chat->user->photo}}" alt="message user image"><!-- /.direct-chat-img -->
	          <div class="direct-chat-text">
	           {!!$chat->text!!}
	          </div>
				@if ($chat->product!="")
						@php
							$images=json_decode($chat->product->images,true);

						@endphp
	
		          <div class="direct-chat-text product">
						<div class="product-frame">
							<div class="product-image-container">
								<img class="product-image" src="{{$images[0]}}">
							</div>
							<div class="product-detail">
								<span class="product-title">{{$chat->product->name}}</span>
								<span class="product-price">Rp. {{ number_format($chat->product->price, 0,',','.') }}</span>
								<span class="product-description">{{$chat->product->description}}</span>
							</div>
							<div class="product-nav">
								<button type="button" class="product-button" onclick="alert('thanks')">Buy this item</button>

							</div>
						</div>

		          </div>
				@endif

	          <!-- /.direct-chat-text -->
	        </div>
	        <!-- /.direct-chat-msg -->  	 
		@endforeach 

	<script type="text/javascript">
		lastNextID={{$chat->id}};

	</script>
@endif