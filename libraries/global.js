/**
 * Created by DanielPHenry on 9/4/2018.
 */
$ = jQuery;

/****************************************************************************
 *
 *  JQuery Plugins.
 *
 ****************************************************************************/

$.fn.once = function(processed_class)
{
  if (typeof processed_class == 'undefined')
  {
    processed_class = 'processed';
  }
  return this.not('.' + processed_class).addClass(processed_class);
};

$.fn.refresh = function(function_pointer)
{
  if (function_pointer !== undefined)
  {
    $(this).on('refresh', '', function_pointer);
  }
  else
  {
    $(this).trigger('refresh');
    return this;
  }
};

$.fn.hasAttr = function(attr_name)
{
  var attr = $(this).attr(attr_name);
  return (typeof attr !== typeof undefined)
};

/****************************************************************************
 *
 *  Form handlers.
 *
 ****************************************************************************/

dnd = {};
$(document).ready(function()
{
  var $menu = $('#menu');
  $menu.find('a').hover(
    function()
    {
      var $this = $(this);
      var $next = $this.next();
      if ($next.prop('tagName') == 'UL')
      {
        $next.css('display', 'block');
        $next.css('left', $this.position().left + 'px');
      }
    },
    function()
    {
      var $this = $(this);
      var $next = $this.next();
      if ($next.prop('tagName') == 'UL')
      {
        menuHide($next);
      }
    });

  $menu.find('ul').hover(
    function()
    {
      clearInterval(dnd.menu_interval);
      dnd.menu_interval = false;
    },
    function()
    {
      menuHide($(this));
    });

  dnd.menu_interval = false;
  function menuHide($target)
  {
    if (!dnd.menu_interval)
    {
      dnd.menu_interval = setTimeout(function()
      {
        $target.css('display', 'none');
        dnd.menu_interval = false;
      }, 400);
    }
  }
});

function modalShow($content)
{
  var $body = $('body');
  var $cover = $('#cover');
  var $modal = $('#modal');
  if (!$cover.length)
  {
    $body.append($('<div id="cover"></div>'));
    //$cover = $('#cover');
  }
  if (!$modal.length)
  {
    $body.append($('<div id="modal"><div id="close-modal">close</div></div>'));
    $modal = $('#modal');
  }
  $modal.find('#close-modal').click(function()
  {
    modalHide();
  });
  $modal.append($content);
  return $modal;
}

function modalLoad()
{
  var $modal = modalShow('');
  $modal.addClass('loading');
}

function modalHide()
{
  $('#cover').remove();
  $('#modal').remove();
}

function getUrlParameter(param, url)
{
  var query;
  if (!url)
  {
    query = window.location.search.substring(1);
  }
  else
  {
    query = url.substring(url.indexOf('?') + 1);
  }
  var args = query.split('&');
  for (var i = 0; i < args.length; i++)
  {
    var arg = args[i].split('=');
    if (arg[0] == param)
    {
      return arg[1];
    }
  }
  return false;
}