<!DOCTYPE html>
<html>
<head>
	<title>Spidy Search Page</title>

	<!-- Bootstrap CSS-->
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap/bootstrap.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
	<link rel="icon" href="{!! asset('img/flash.png') !!}"/>
</head>
<body>
	<div class="container">
		<nav class="navbar navbar-default">
  			<div class="container-fluid">
    			<div class="navbar-header">
      				<a class="navbar-brand" href="/">SPIDY</a>
    			</div>
    			<ul class="nav navbar-nav">
      				<li class="active"><a href="/search">Search Page</a></li>
    			</ul>
  			</div>
		</nav>
		<div class="container">
			<h1>Search Page	</h1>
			<form action="/search" method="POST" role="search">
				{{ csrf_field() }}
					<div  class="input-group">
						<input type="text" class="form-control" name="q" placeholder="Search here" required>
						<span class="input-group-btn">
							<button type="submit" class="btn btn-default">
								<span>Search<!--	Add glyphicon here	--></span>
							</button>
						</span>
					</div>
			</form>
		</div>

		<!--	Output	-->
		<div class="container">
			<table class="table">
				@if(isset($hits))
					<th>
						<tr>
							<td><b>Search Result for : {{ $searchValue }}</b></td>
						</tr>
					</th>
					@if($hits > 0)
						@if($hits > 1)
							@for($i = 0 ; $i < count($responses['hits']['hits']); $i++)
								<tr>
									<td>
										<p>
											{{ $responses['hits']['hits'][$i]['_source']['file_name'] }}
										</p>
									</td>
								</tr>
							@endfor
							@for($i = 0 ; $i <= $hits-2; $i++)
								@for($j = 0 ; $j < count($responses[$i]['hits']['hits']) ;$j++)
									<tr>
										<td>
											<p>
												{{ $responses[$i]['hits']['hits'][$j]['_source']['file_name'] }}
											</p>
										</td>
									</tr>
								@endfor
							@endfor
						@else
							@for($i = 0 ; $i < count($responses['hits']['hits']) ;$i++)
								<tr>
									<td>
										<p>
											{{ $responses['hits']['hits'][$i]['_source']['file_name'] }}
										</p>		
									</td>
								</tr>
							@endfor
						@endif
					@endif
					@if($hits == 0)
						<tr>
							<td>
								<p>
									{{ $responses }}
								</p>
							</td>
						</tr>
					@endif
				@endif
			</table>
		</div>
	</div>

	<!-- Bootstrap JS -->
	<script type="text/javascript" src="{{ asset('js/bootstrap/bootstrap.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
</body>
</html>