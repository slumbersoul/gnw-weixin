<?php if( !function_exists('pagination_url') ){
function pagination_url($url_options, $page=1){
    $url_options['page'] = $page;
    if(empty($url_options['hardLink'])){
        return url($url_options);
    }
    else{   
        $link = $url_options['hardLink'];
        unset($url_options['hardLink']);
        return $link.'?'.http_build_query($url_options); 
    }
} }  //END func pagination_url


if ($pagination->totalPages > 1):
    $url_options = $pagination->urlOptions;
?>

<!-- Previous page link --> 
<?php if ( $pagination->prevPage): ?> 
  <a href="<?php echo pagination_url( $url_options, $pagination->prevPage ); ?>">&lt;上一页</a>
<?php endif; ?> 

<!-- Numbered page links -->
<?php 
    $page_counter = 1; 
    $page_total = count($pagination->pages);
?>

<?php foreach ($pagination->pages as $page_num=>$is_current_page  ): ?>
  <?php if(1==$page_counter AND $page_num!=$pagination->firstPage ): ?> 
    <a href="<?php echo pagination_url($url_options, $pagination->firstPage); ?>"><?php echo $pagination->firstPage; ?></a>
    <?php if(2!=$page_num): ?><i>...</i><?php endif; ?>
  <?php endif; ?>

  <?php if ( !$is_current_page): ?>
    <a href="<?php echo pagination_url($url_options, $page_num); ?>"><?php echo $page_num; ?></a> 
  <?php else: ?>
    <a class="c"><?php echo $page_num;?></a>
  <?php endif; ?>


  <?php if($page_total==$page_counter AND $page_num!=$pagination->lastPage ): ?> 
    <?php if($pagination->lastPage-1!=$page_num): ?><i>...</i><?php endif; ?>
    <a href="<?php echo pagination_url($url_options, $pagination->lastPage); ?>"><?php echo $pagination->lastPage; ?></a> 
  <?php endif; ?>

<?php $page_counter++; endforeach; ?>

<!-- Next page link --> 
<?php if ( $pagination->nextPage ): ?> 
   <a href="<?php echo pagination_url($url_options, $pagination->nextPage); ?>">下一页&gt;</a>
<?php endif; ?> 


<?php endif; ?>
