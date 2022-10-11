const sidebarToggle = document.body.querySelector('#sidebarToggle');
if (sidebarToggle) {
    // Uncomment Below to persist sidebar toggle between refreshes
    // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
    //     document.body.classList.toggle('sb-sidenav-toggled');
    // }
    sidebarToggle.addEventListener('click', event => {
        event.preventDefault();
        document.body.classList.toggle('sb-sidenav-toggled');
        localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
    });
}

if (document.getElementsByClassName('intro')) {
    window.addEventListener("DOMContentLoaded", (event) => {
        animate_text();


    });

// -------------------
    function animate_text() {
        let delay = 20,
            delay_start = 0,
            contents,
            letters;

        document.querySelectorAll(".intro").forEach(function (elem) {
            contents = elem.textContent.trim();
            elem.textContent = "";
            letters = contents.split("");
            elem.style.visibility = 'visible';

            letters.forEach(function (letter, index_1) {
                setTimeout(function () {
                    // ---------
                    // effet machine à écrire (SIMPLE)
                    elem.textContent += letter;
                    // ---------
                    // OU :
                    // effet machine à écrire + animation CSS (SPECIAL)
                    /*
                    var span = document.createElement('span');
                    span.innerHTML = letter.replace(/ /,'&nbsp;');
                    elem.appendChild(span);
            */
                    // ---------

                    //fondu('projet'+id.match(/(\d+)/));
                }, delay_start + delay * index_1);


            });
            delay_start += delay * letters.length;
            let id = elem.id
            //  console.log('projet' + parseInt(id.match(/(\d+)/)));
            //    fondu('projet'+parseInt(id.match(/(\d+)/)))
            //   console.log(delay_start);
            setTimeout(function () {
                fondu('projet' + parseInt(elem.id.match(/(\d+)/)))
            }, delay_start);
        });
    }


}


function fondu(nomDiv) {
    let div = document.getElementById(nomDiv).style;// récupère div
    let i = 0;// initialise i
    let f = function ()// attribut à f une fonction anonyme
    {
        div.opacity = i;// attribut à l'opacité du div la valeur d'i
        i = i + 0.02;// l'incrémente

        if (i <= 1)// si c'est toujours pas égal à 1
        {
            setTimeout(f, 20);// attend 20 ms, et relance la fonction
        }
    };

    f();// l'appel une première fois pour lancer la boucle
}

//petit js qui permet la transition de nav en noir lorsqu'on scroll 
$(window).scroll(function () {
    var scroll = $(window).scrollTop();
    if (scroll > 90) {
        $("nav").addClass("bg-nav-dark");
    } else {
        $("nav").removeClass("bg-nav-dark");

    }
})

$(window).ready(function () {
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
if (document.getElementById('excel')) {
    $('#excel').DataTable({


        "pageLength": 10,
        responsive: true

    });
}
$("#contactme").submit(function (e) {
    e.preventDefault(); //empêcher une action par défaut
    const form_url = $(this).attr("action"); //récupérer l'URL du formulaire
    const form_method = "POST"; //récupérer la méthode GET/POST du formulaire
    const form_data = $(this).serialize(); //Encoder les éléments du formulaire pour la soumission
    $('#submit').prop('disabled', true);

    let option = $("#contact_sujet").children("option:selected").val();
    if (option === "cv") {

    } else {
        if (!$('#contact_message').val()) {

            $("#res").html(`<div class="alert alert-warning" role="alert">
            Merci de bien remplir tous les champs
            </div>`);
            $('#submit').prop('disabled', false);
            return false;
        }
    }
    //console.log(form_data);
    $.ajax({
        url: form_url,
        type: form_method,
        data: form_data
    }).done(function (response) {
        //console.log(response);
        // let dataJson = $.parseJSON(response);
        // console.log(dataJson);
        if (response.error !== "") {
            $("#res").html(`<div class="alert alert-danger" role="alert">
  ${response.error}
</div>`);
            $('#submit').prop('disabled', false);
        } else {
            $("#res").html(`<div class="alert alert-success" role="alert">
            ${response.success}
            </div>`);
            $("#contactme").css("display", "none");
            setTimeout(function () {
                $("#res").html("");
                $("#contact_sujet").val('0').change();
                $("#contactme").css("display", "");
                $('#contact_email').val("");
                $('#contact_message').val("");
                $('#submit').prop('disabled', false);
            }, 8000)
        }
        //  $("#res").html();

    }).fail(function () {
        $('#submit').prop('disabled', false);
        $("#res").html(`<div class="alert alert-success" role="alert">
            Merci de bien remplir tous les champs
            </div>`);
    });
});
$('#contact_message').attr('required', true);
$(document).ready(function () {
    $("#contact_sujet").change(function () {
        let option = $(this).children("option:selected").val();

        if (option === "cv") {
            document.getElementById("boxmessage").style.display = "none";
            $('#contact_message').attr('required', false);
        } else {
            document.getElementById("boxmessage").style.display = "block";
            $('#contact_message').attr('required', true);
        }


    });
});

$('#closewindows').click(function () {

    document.getElementById("console").style.display = "none";
});

$('#maximize').click(function () {
    //overlay

    $('#console').addClass('overlay').removeClass('console');
});
$('#minimize').click(function () {
    $('#console').addClass('console').removeClass('overlay');
});