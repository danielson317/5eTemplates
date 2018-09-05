/**
 * Created by DanielPHenry on 9/4/2018.
 */
$ = jQuery;

$(document).ready(function()
{
  var $menu = $('#menu');
  $menu.find('a').hover(function()
  {
    var $this = $(this);
    var $next = $this.next();
    if ($next.prop('tagName') == 'UL')
    {
      $next.css('display', 'block');
      $next.css('left', $this.position().left + 'px');
    }
  });

  $menu.find('ul').mouseleave(function()
  {
    //console.log('out');
    $(this).css('display', 'none');
  });
});