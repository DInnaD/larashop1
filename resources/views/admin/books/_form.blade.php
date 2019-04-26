<div class="form-group">
    {!!Form::label('name', 'Name') !!}
    {!!Form::text('name', null, ['class' => 'form-control']) !!}
    {!!Form::label('author_name', 'Author Name') !!}
    {!!Form::text('author_name', null, ['class' => 'form-control']) !!}
    {!!Form::label('lenght', 'Lenght') !!}
    {!!Form::text('lenght', null, ['class' => 'form-control']) !!}
    {!!Form::label('publisher', 'Publisher') !!}
    {!!Form::text('publisher', null, ['class' => 'form-control']) !!}
    {!!Form::label('year', 'Year') !!}
    {!!Form::text('year', null, ['class' => 'form-control']) !!}
    {!!Form::label('format', 'Format') !!}
    {!!Form::text('format', null, ['class' => 'form-control']) !!}
    {!!Form::label('genre', 'Genre') !!}
    {!!Form::text('genre', null, ['class' => 'form-control']) !!}
    {!!Form::label('dimensions', 'Dimensions') !!}
    {!!Form::text('dimensions', null, ['class' => 'form-control']) !!}
    {!!Form::label('price', 'Price') !!}
    {!!Form::text('price', null, ['class' => 'form-control']) !!}
    {!!Form::label('old_price', 'Old Price') !!}
    {!!Form::text('old_price', null, ['class' => 'form-control']) !!}
    {!!Form::label('img', 'Image') !!}
    {!!Form::text('img', null, ['class' => 'form-control']) !!}
    {!!Form::label('code', 'Code') !!}
    {!!Form::text('code', null, ['class' => 'form-control']) !!}
    {!!Form::label('discont_global', 'Global Discon') !!}
    {!!Form::text('discont_global', null, ['class' => 'form-control']) !!}
    {!!Form::label('discont_id', 'User Discont') !!}
    {!!Form::text('discont_id', null, ['class' => 'form-control']) !!}

	{!!Form::label('is_hard_cover', 'Hard Cover') !!}
    {!!Form::checkbox('HardCover?', 'is_hard_cover', true) !!}
 </div>             
 <div class="form-group">
              <label>Категория</label>
              {{Form::select('category_id', 
              	$categories, 
              	null, 
              	['class' => 'form-control select2'])
              }}
            </div>
            <div class="form-group">
              <label>Теги</label>
              {{Form::select('tags[]', 
              	$tags, 
              	null, 
              	['class' => 'form-control select2', 'multiple'=>'multiple','data-placeholder'=>'Выберите теги'])
              }}
            </div>
            <!-- checkbox -->
            <div class="form-group">
              <label>
                <input type="checkbox" class="minimal" name="is_hard_cover">
              </label>
              <label>
                Черновик
              </label>
            </div>
          </div>