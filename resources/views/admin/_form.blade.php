<div class="form-group">
    {!!Form::label('', '') !!}
    {!!Form::text('', null, ['class' => 'form-control']) !!}

	{{-- DROPDOWN --}}

	{!! Form::label('template_id', 'Template') !!}

	{!! Form::select(
	 'template_id',
	 \App\Template::getSelectList('name_template', 'id_template'),
	 isset($compaign) ? $compaign->template_id : null,
	 ['class' => 'form-control']); !!}

	{!! Form::label('bunch_id', 'bunch') !!}
	{!! Form::select(
	 'bunch_id',
	 \App\Bunch::getSelectList('name_bunch', 'id_bunch'),
	 isset($compaign) ? $compaign->bunch_id : null,
	 ['class' => 'form-control']); !!}
    
    {!!Form::label('description_compaign', 'Description') !!}
    {!!Form::text('description_compaign', null, ['class' => 'form-control']) !!}

</div>
public function getSubscribersList(){
        return implode(', ', $this->getRecepients());
	}

	public function getRecepients(){
		return array_column($this->bunch->subscribers, 'email_subscriber');
	}

	<div class="form-group">
    {!!Form::label('subject', 'Subject') !!}
    {!!Form::text('subject', null, ['class' => 'form-control']) !!}
    {!!Form::label('to', 'To') !!}
    {!!Form::text('to', null, ['class' => 'form-control']) !!}
    {!!Form::label('from', 'From') !!}
    {!!Form::text('from', null, ['class' => 'form-control']) !!}
    {!!Form::label('message', 'Message') !!}
    {!!Form::textarea('message', null, ['class' => 'form-control']) !!}
     {!!Form::label('survey', 'Unsubscribe') !!}
    {!!Form::text('survey', null, ['class' => 'form-control']) !!}
</div>