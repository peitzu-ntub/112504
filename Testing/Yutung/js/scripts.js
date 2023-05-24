$(document).ready(function () {
  var valueNum = window.sessionStorage.userdata;
  if (valueNum != "1") {
    $("#myModal").modal();
  }
});
function cancel() {
  window.sessionStorage.userdata = "1";
}
function show() {
  window.sessionStorage.userdata = "0";
  $("#myModal").modal();
}

//數量加減
$(function () {
  // This button will increment the value
  $('.qtyplus').click(function (e) {
    // Stop acting like a button
    e.preventDefault();
    // Get the field name
    fieldName = $(this).attr('field');
    // Get its current value
    var currentVal = parseInt($('input[name=' + fieldName + ']').val());
    // If is not undefined
    if (!isNaN(currentVal)) {
      // Increment
      $('input[name=' + fieldName + ']').val(currentVal + 1);
    } else {
      // Otherwise put a 0 there
      $('input[name=' + fieldName + ']').val(0);
    }
  });
  // This button will decrement the value till 0
  $(".qtyminus").click(function (e) {
    // Stop acting like a button
    e.preventDefault();
    // Get the field name
    fieldName = $(this).attr('field');
    // Get its current value
    var currentVal = parseInt($('input[name=' + fieldName + ']').val());
    // If it isn't undefined or its greater than 0
    if (!isNaN(currentVal) && currentVal > 0) {
      // Decrement one
      $('input[name=' + fieldName + ']').val(currentVal - 1);
    } else {
      // Otherwise put a 0 there
      $('input[name=' + fieldName + ']').val(0);
    }
  });
});




// 評價
var MARK_INFO = [
  '1分|很不滿意|',
  '2分|不滿意|',
  '3分|一般|',
  '4分|滿意|',
  '5分|非常滿意|'
];


function delegateEvent(delegateElement, targetTag, eventName, handler) {
  delegateElement.addEventListener(eventName, function (event) {
    var target = event.target;
    if (target.nodeName.toLowerCase() === targetTag.toLowerCase()) {
      return handler(event);
    }
  }, false);
}

function hasClass(element, className) {
  return (new RegExp('(^|\\s)' + className + '($|\\s)')).test(element.className);
}

function addClass(element, newClassName) {
  if (!hasClass(element, newClassName)) {
    element.className += element.className ? (' ' + newClassName) : newClassName;
  }
}

function removeClass(element, oldClassName) {
  if (hasClass(element, oldClassName)) {
    element.className = element.className.replace(new RegExp('(^|\\s)' + oldClassName + '($|\\s)'), ' ').trim();
  }
}

function lightenStar(stars, activeIndex) {
  for (var i = 0; i <= activeIndex; i++) {
    addClass(stars[i], 'light');
  }
}

function darkenStar(stars) {
  for (var i = 0; i < stars.length; i++) {
    removeClass(stars[i], 'light');
  }
}

var starMark = document.getElementsByClassName('star-mark')[0];
var stars = starMark.getElementsByClassName('star')[0].getElementsByTagName('li');
var helpInfo = starMark.getElementsByClassName('help-info')[0];
var cnofirmIndex = -1;

delegateEvent(starMark, 'li', 'click', function (event) {
  var target = event.target;
  var markResult = starMark.getElementsByClassName('result')[0];
  cnofirmIndex = Array.prototype.indexOf.call(stars, target);
  lightenStar(stars, cnofirmIndex);

  var markNumDiv = markResult.getElementsByClassName('mark')[0];
  var markDetailDiv = markResult.getElementsByClassName('detail')[0];
  var infos = MARK_INFO[cnofirmIndex].split('|');
  markNumDiv.textContent = infos[0];
  markDetailDiv.textContent = '（' + infos[2] + '）';
});

delegateEvent(starMark, 'li', 'mouseover', function (event) {
  var target = event.target;
  hoverIndex = Array.prototype.indexOf.call(stars, target);
  darkenStar(stars);
  lightenStar(stars, hoverIndex);

  helpInfo.style.display = 'block';
  helpInfo.style.left = (hoverIndex + 1) * 25 + 'px';

  var helpMark = helpInfo.getElementsByClassName('mark')[0];
  var helpDescri = helpInfo.getElementsByClassName('decri')[0];
  var helpDetail = helpInfo.getElementsByClassName('detail')[0];
  var infos = MARK_INFO[hoverIndex].split('|');
  helpMark.textContent = infos[0];
  helpDescri.textContent = infos[1];
  helpDetail.textContent = infos[2];
});

delegateEvent(starMark, 'li', 'mouseout', function (event) {
  var target = event.target;
  darkenStar(stars);
  lightenStar(stars, cnofirmIndex);
  helpInfo.style.display = 'none';
});