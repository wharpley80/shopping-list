$(function() {

  // Initialize tooltipster on signin-form input/password elements
  $('.signin-form input[type="text"], .signin-form input[type="password"]').tooltipster({ 
    trigger: 'custom', // default is 'hover' which is no good here
    onlyOne: false,    // allow multiple tips to be open at a time
    position: 'right'  // display the tips to the right of the element
  });

  $(".signin-form").validate({
    rules: {
      username: {
        required: true,
        minlength: 5,
        remote: {
          url: "../valid_username.php",
          type: "POST" 
        } 
      },
      password: {
        required: true,
        remote: {
          url: "../valid_password.php",
          type: "POST",
          data: {
            username: function() {
              return $("#username").val();
            }
          }
        } 
      }
    },  
    messages: {
      username: {
        required: "Username Required",
        minlength: "5 Characters Required",
        remote: "Invalid Username."     
      },
      password: {
        required: "Password Required",
        remote: "Invalid Password."
      }
    },
    errorPlacement: function (error, element) {
      $(element).tooltipster('update', $(error).text());
      $(element).tooltipster('show');
      $('#signIn').on('hidden.bs.modal', function () {
        $(element).tooltipster('hide');
      });
    },
    success: function (label, element) {
      $(element).tooltipster('hide');
    }
  });

  // Initialize tooltipster on signup-form input/password elements
  $('.signup-form input[type="text"], .signup-form input[type="password"]').tooltipster({ 
    trigger: 'custom', // default is 'hover' which is no good here
    onlyOne: false,    // allow multiple tips to be open at a time
    position: 'right'  // display the tips to the right of the element
  });

  $(".signup-form").validate({
    rules: {
      newname: {
        required: true,
        minlength: 5, 
        maxlength: 15, 
        remote: {
          url: "../check_username.php",
          type: "POST" 
        } 
      },
      'new-password': {
        required: true,
        minlength: 6
      },
      'password-confirm': {
        equalTo: "#new-password"
      }
    },  
    messages: {
      newname: {
        required: "Username Required",
        minlength: "5 Characters Required",
        maxlength: "15 Characters Max",
        remote: "This Username Already Exists"      
      },
      'new-password': {
        required: "Password Required",
        minlength: "6 Characters Required"
      },
      'password-confirm': {
        required: "Password Required",
        minlength: "6 Characters Required",
        equalTo: "Passwords Don't Match"
      },
    },
    errorPlacement: function (error, element) {
      $(element).tooltipster('update', $(error).text());
      $(element).tooltipster('show');
      $('#signUp').on('hidden.bs.modal', function () {
        $(element).tooltipster('hide');
      });
    },
    success: function (label, element) {
      $(element).tooltipster('hide');
    }
  });

 // Initialize tooltipster on item-form input/password elements
  $('.item-form select').tooltipster({ 
    trigger: 'custom', // default is 'hover' which is no good here
    onlyOne: false,    // allow multiple tips to be open at a time
    position: 'right'  // display the tips to the right of the element
  });

  $(".item-form").validate({
    rules: {
      list: {
        required: true,
      }
    },  
    messages: {
      list: {
        required: "Select List",     
      }
    },
    errorPlacement: function (error, element) {
      $(element).tooltipster('update', $(error).text());
      $(element).tooltipster('show');
    },
    success: function (label, element) {
      $(element).tooltipster('hide');
    }
  });

  // Edit Username pop-up
  $(function() {
  function deselect(j) {
    $('.tog').slideFadeToggle(function() {
      j.removeClass('selected');
    });    
  }
 
  $('#changename').on('click', function(event) {
    event.preventDefault();

    if($(this).hasClass('selected')) {
       deselect($(this));               
    } else {
       $(this).addClass('selected');
       $('.tog').slideFadeToggle();
    }    
  });
  
  $('.close').on('click', function(event) {
    event.preventDefault();
    deselect($('#changename'));
   });
});

// New List pop-up
$(function() {
  function deselect(e) {
    $('.list').slideFadeToggle(function() {
      e.removeClass('seleceted');
    });    
   }

  $('#newlist').on('click', function(event) {
    event.preventDefault();

    if($(this).hasClass('selected')) {
      deselect($(this));               
    } else {
      $(this).addClass('selected');
      $('.list').slideFadeToggle();
    }    
  });
});

$.fn.slideFadeToggle = function(easing, callback) {
  return this.animate({ opacity: 'toggle', height: 'toggle' }, 'fast', easing, callback);
};

  // Clears a User's entire list.
  $('a#clear').on('click', function(event) {
    event.preventDefault();
    
    if (confirm("Are you sure you want to clear your list?")) {
      var items = $('h1').closest('div');
      var id = items.find('span').data('id');
      var clr = $('h2').closest('div');
      var lis = clr.find('span').data('lis');
      $('.fulllist').fadeOut();
    
      $.ajax({
        type: "POST",
        url: "../delete_items.php",
        data: {
          id: id,
          lis: lis
        },
      });
     } 
   }); 
   
  // Removes a specific item.
  $('a.byebye').on('click', function( event ) {
    event.preventDefault();
    
    var parent = $(this).closest('div');
    var id = parent.find('span').data('id');
    parent.fadeOut();

    $.ajax({
      type: "POST",
      url: "../delete_row.php",
      data: {
        id: id
      },
    });
  });  
});

// Deletes a Specific List.
$(function() {
  $('a#deletelist').on('click', function(event) {
    event.preventDefault();
  
    if (confirm("Are you sure you want to delete this list?")) {
	    var user = $('h1').closest('div');
      var id = user.find('span').data('id');
      var remove = $('h2').closest('div');
      var lis = remove.find('span').data('lis');
    
      $.ajax({
        type: "POST",
        url: "../delete_list.php",
        data: {
	        id: id,
          lis: lis
        },
		
		success: function() {
		    window.location.href = '../list/';
		}
      });
    }
  });
});

// Deletes Username from list.
$(function() {
  $('a#deletename').on('click', function(event) {
    event.preventDefault();
  
    if (confirm("Are you sure you want to delete this shopper?")) {
      var erase = $('h1').closest('div');
      var ids = erase.find('span').data('id');
      
      $.ajax({
        type: "POST",
        url: "../delete_name.php",
        data: {
          ids: ids
        },
		
		success: function() {
		    window.location.href = '../signin/';
		}
      });
    }
  });
});

