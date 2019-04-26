@extends('layouts.panel')
<?php  /** @var \Illuminate\Support\ViewErrorBag $errors    templates_*/  ?>
@section('panel')
	<div>
		<div>
			<a href="{{route('/')}}"><i></i>Back</a>
			<div><b>New</b></div>
		</div>
	</div>
	<div>
		{!! Form::open(['route' => '.store', '' => $]) !!}	
		<input type="text">
		<div>
			{!! Form::button('Create', ['type' => 'Submit', 'class' => 'btn btn-primary']) !!}
		</div>
		{!! Form::close() !!}
	</div>
	<!--div>div>a[href={{route('/')}}]>i^div>b^^div>div>input+div-->
@endsection
