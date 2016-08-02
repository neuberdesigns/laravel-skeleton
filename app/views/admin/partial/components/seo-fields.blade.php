<!-- <fieldset>
	<legend>SEO</legend> -->
	{{InputFactory::create('text')->name('title', 'Titulo')->size(8)->build()}}
	{{InputFactory::create('text')->name('keywords', 'Palavras chave')->size(6)->build()}}
	{{InputFactory::create('text')->name('description', 'Meta Descrição')->size(10)->build()}}
	
	<input type="hidden" name="object_id" />
<!-- </fieldset> -->
