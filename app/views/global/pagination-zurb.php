<?php
	$presenter = new ZurbPresenter($paginator);
?>

<?php if ($paginator->getLastPage() > 1): ?>
	<div class="pagination-wrapper text-center">
		<ul class="pagination">
		    <?php echo $presenter->render(); ?>
		</ul>
	</div>
<?php endif; ?>
