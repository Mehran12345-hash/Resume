  $(".t").mouseenter(function(){
    alert("Mouse Enter");
  });
  $(".q").mouseleave(function(){
    alert("MOuseleave");
  });
  $(".e").hover(function(){
    alert("Hover");
  },
  function(){
    alert("Hover leave");
  });   
  $("input").focus(function(){
    $(this).css("background-color", "yellow");
  });
  $("input").blur(function(){
    $(this).css("background-color", "green");
  });
  $("p,#p").on({
  mouseenter: function(){
    $(this).css("background-color", "yellow");
    },
    mouseenter: function(){
      $(this).css("background-color", "lightgray");
    },
    mouseleave: function(){
      $(this).css("background-color", "lightblue");
    },
    click: function(){
      $(this).css("background-color", "yellow");
    },
  });
  $(".tbutton,#f,.qq,#btn5,#btn4,#btn3,#btn2,#btn1").on({
    mouseenter: function(){
      $(this).css("background-color", "yellow");
      },
      mouseenter: function(){
        $(this).css("background-color", "green");
      },
      mouseleave: function(){
        $(this).css("background-color", "white");
      },
      click: function(){
        $(this).css("background-color", "yellow");
      },
    });
  $(".qq").click(function(){
    $(".w").toggle();
  });

  $("#f").click(function(){
    $("#p").slideToggle("slow");
  });
  
  $(".tbutton").click(function(){
    $(".d").hide("slow", function(){
      alert("The paragraph is now hidden");
    });
    });
    $("#btn1").click(function(){
      $("#test1").text("Khan Saib");
    });
    $("#btn2").click(function(){
      $("#test2").html("<b>Login</b>");
    });
    $("#btn3").click(function(){
      $("#test3").val("Mehran");
    });
    $("#btn4").click(function(){
      $("#test4").val("KHan@gmail.com");
    });
    $("#btn5").click(function(){
      $("#test5").val("Password");
    });

    
    $("#name, #email").on("input", function(){
      var name =$("#name").val();
      var email = $("#email").val();

      $("#displayname").text("NAME:" + name);
      $("#displayemail").text("Email:" + email)
    });
    //Selected
$("#phone").on("change",function(){
  $(".message").text("You Selected: " +$(this).val());
});
  

  
