//Ajax enabled PhoneGap apps Must include this file
jQuery('[data-role=page]').live('pagecreate',function(){
  jQuery('a').each(function(){
    var href=jQuery(this).attr('href');
    if(href.search(/^http/) == -1)
    {
      jQuery(this).attr('href',href.replace('.php','.html'));
    }
  });
});
