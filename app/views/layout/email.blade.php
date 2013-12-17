{{-- $about->abo_fianceName --}}
<table width="550" style="background-color: #FF2F8A; margin: 0 auto; border-collapse: collapse;">
	<tr>
		<td>
			{{HTML::image(asset('images/icon/wedding_rings_128.png'))}}
		</td>
		
		<td>
			{{FileUpload::getTim( $about->abo_brideImage, 100, 100 )}}
		</td>
		
		<td>
			{{FileUpload::getTim( $about->abo_fianceImage, 100, 100 )}}
		</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	@yield('content')
</table>