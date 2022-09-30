var div1 = document.getElementById("jarditou").style;// récupère div
div1.opacity = 0;
var div2 = document.getElementById("pblv-scoop").style;// récupère div
div2.opacity = 0;
var div3 = document.getElementById("travaildeformation").style;// récupère div
div3.opacity = 0;
var div4 = document.getElementById("jarditouwordpress").style;// récupère div
div4.opacity = 0;

var div5 = document.getElementById("wazaaimmo").style;// récupère div
div5.opacity = 0;
let myText = "git clone https://github.com/Lefhar/CodeIgniter_exercice.git";
let nbrtext = myText.length;
let myArray = myText.split("");
let timeLooper;
let i = 0;
        function loop(){

            if(myArray.length > 0){
                document.getElementById("intro").innerHTML += myArray.shift();
                i++;
            }else{
            clearTimeout(timeLooper);
            }
           
            timeLooper = setTimeout('loop()',70);
      
            if(nbrtext == i){
                clearTimeout(timeLooper);
                 document.getElementById("intro").innerHTML = 'git clone <a href="https://github.com/Lefhar/CodeIgniter_exercice.git" target="_blank">https://github.com/Lefhar/CodeIgniter_exercice.git</a>';
                fondu("jarditou");
                loop2();
            }
}

loop();


let myText2 = "wget https://pblv-scoop.com";
let nbrtext2 = myText2.length;
let myArray2 = myText2.split("");
let timeLooper2;
let i2 = 0;
        function loop2(){

            if(myArray2.length > 0){
                document.getElementById("intro2").innerHTML += myArray2.shift();
                i2++;
            }else{
            clearTimeout(timeLooper2);
            }
           
            timeLooper2 = setTimeout('loop2()',70);
      
            if(nbrtext2 == i2){
                clearTimeout(timeLooper2);
               document.getElementById("intro2").innerHTML = 'wget <a href="https://pblv-scoop.com" target="_blank">https://pblv-scoop.com</a>';
                fondu("pblv-scoop");
                loop3();
            }
}


let myText3 = "git clone https://github.com/Lefhar/travaildeformation.git";
let nbrtext3 = myText3.length;
let myArray3 = myText3.split("");
let timeLooper3;
let i3 = 0;
        function loop3(){

            if(myArray3.length > 0){
                document.getElementById("intro3").innerHTML += myArray3.shift();
                i3++;
            }else{
            clearTimeout(timeLooper3);
            }
           
            timeLooper3 = setTimeout('loop3()',70);
      
            if(nbrtext3 == i3){
                clearTimeout(timeLooper3);
               document.getElementById("intro3").innerHTML = 'git clone <a href="https://github.com/Lefhar/travaildeformation.git" target="_blank">https://github.com/Lefhar/travaildeformation.git</a>';
                fondu("travaildeformation");
                loop4();
                 //fonduinverse("console");
            }
}


let myText4 = "git clone https://github.com/Lefhar/template_wp_jarditou.git";
let nbrtext4 = myText4.length;
let myArray4 = myText4.split("");
let timeLooper4;
let i4 = 0;
        function loop4(){

            if(myArray4.length > 0){
                document.getElementById("intro4").innerHTML += myArray4.shift();
                i4++;
            }else{
            clearTimeout(timeLooper4);
            }
           
            timeLooper4 = setTimeout('loop4()',70);
      
            if(nbrtext4 == i4){
                clearTimeout(timeLooper4);
               document.getElementById("intro4").innerHTML = 'git clone <a href="https://github.com/Lefhar/template_wp_jarditou.git" target="_blank">https://github.com/Lefhar/template_wp_jarditou.git</a>';
                fondu("jarditouwordpress");
                loop5();
                 //fonduinverse("console");
            }
}



let myText5 = "git clone https://github.com/Lefhar/wazaaimmo.git";
let nbrtext5 = myText5.length;
let myArray5 = myText5.split("");
let timeLooper5;
let i5 = 0;
        function loop5(){

            if(myArray5.length > 0){
                document.getElementById("intro5").innerHTML += myArray5.shift();
                i5++;
            }else{
            clearTimeout(timeLooper5);
            }
           
            timeLooper5 = setTimeout('loop5()',70);
      
            if(nbrtext5 == i5){
                clearTimeout(timeLooper5);
               document.getElementById("intro5").innerHTML = 'git clone <a href="https://github.com/Lefhar/wazaaimmo.git" target="_blank">https://github.com/Lefhar/wazaaimmo.git</a>';
                fondu("wazaaimmo");
                 //fonduinverse("console");
            }
}





function fondu(nomDiv)
{
  var div = document.getElementById(nomDiv).style;// récupère div
  var i = 0;// initialise i
  var f = function()// attribut à f une fonction anonyme
  {
    div.opacity = i;// attribut à l'opacité du div la valeur d'i
    i = i+0.02;// l'incrémente
     
    if(i<=1)// si c'est toujours pas égal à 1
    {
      setTimeout(f,20);// attend 20 ms, et relance la fonction
    }
  };
   
  f();// l'appel une première fois pour lancer la boucle
}

function fonduinverse(nomDiv)
{
  var div = document.getElementById(nomDiv).style;// récupère div
  var i = 100;// initialise i
  var f = function()// attribut à f une fonction anonyme
  {
    div.opacity = i;// attribut à l'opacité du div la valeur d'i
    i = i-0.8;// l'incrémente
     
    if(i>=0)// si c'est toujours pas égal à 1
    {
      setTimeout(f,20);// attend 20 ms, et relance la fonction
    }
    if(i < 0){
        document.getElementById(nomDiv).style.display = "none";
    }
  };
   
  f();// l'appel une première fois pour lancer la boucle
}


//petit js qui permet la transition de nav en noir lorsqu'on scroll 
    $(window).scroll(function(){
        var scroll = $(window).scrollTop();
        if (scroll > 90) {
          $( "nav" ).addClass( "bg-nav-dark" );
         } else{
            $( "nav" ).removeClass( "bg-nav-dark" );	
        
        }
    })

 $(window).ready(function ()  
        { 
            $('html, body').animate({ 
                scrollTop: '0px' 
            }, 
            1500); 
            return false; 
        }); 
    
        $(document).on('click', 'a[href^="#"]', function (event) {
            event.preventDefault();
        
            $('html, body').animate({
                scrollTop: $($.attr(this, 'href')).offset().top
            }, 500);
        });

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
       
            document.body.innerHTML = printContents;
       
            window.print();
       
            document.body.innerHTML = originalContents;
       }



      $("#contactme").submit(function(e){
        e.preventDefault(); //empêcher une action par défaut
        var form_url = $(this).attr("action"); //récupérer l'URL du formulaire
        var form_method = "POST"; //récupérer la méthode GET/POST du formulaire
        var form_data = $(this).serialize(); //Encoder les éléments du formulaire pour la soumission
        console.log(form_data);
        $.ajax({
          url : form_url,
          type: form_method,
          data : form_data
        }).done(function(response){ 
          $("#res").html(response);
        });
      });
      $(document).ready(function(){
        $("#sujet").change(function(){
          var option = $(this).children("option:selected").val();
       if(option =="cv"){

        document.getElementById("boxmessage").style.display = "none";
       }else{
        document.getElementById("boxmessage").style.display = "block";
       }


        });
      });

      $('#closewindows').click(function(){
  
        document.getElementById("console").style.display = "none";
      });

      $('#maximize').click(function(){
  //overlay

        $('#console').addClass('overlay').removeClass('console');
      });
      $('#minimize').click(function(){
      $('#console').addClass('console').removeClass('overlay');
      });