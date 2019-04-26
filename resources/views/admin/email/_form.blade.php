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