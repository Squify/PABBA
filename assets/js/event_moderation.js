import { Button } from "bootstrap";
import Swal from "sweetalert2";

$(function () {

    $(".validate-btn").each(function(index){
        $(this).on("click", (e) => {
            e.preventDefault();
            let { event, title } = $(".validate-btn")[index].dataset;
            validateSwal(event, title);
        })
    });



    $(".refuse-btn").each(function(index){
        $(this).on("click", (e) => {
            e.preventDefault();
            let { event, title } = $(".refuse-btn")[index].dataset
            refuseSwal(event, title);
        });
    })
    
    $(".see-reason").each(function(index){
        $(this).on("click", ()=>{
            let { reason } = $(".see-reason")[index].dataset
            reasonSwal(reason);
        })
    })

})

function validateSwal(event, title) {

    Swal.fire({
        title: "Validation",
        text: "Êtes-vous certains de vouloir confirmer la publication de cet événement : " + title,
        icon: "warning",
        showConfirmButton: false,
        footer: `<button id="yes-btn" class="btn btn-success mr-3">Oui</button>` +
            `<button id="no-btn" class="btn btn-danger mr-3">Non</button>` +
            `<a href="${basePath}/${event}" target="_blank" class="btn btn-primary">Plus de détails</button>`,
        didRender: (element) => {
            $("#yes-btn").on("click", () => {
                validate(event);
                Swal.close();
            })
            $("#no-btn").on("click", () => {
                // Si on clique sur "Non" on ferme juste la Swal
                Swal.close();
            })
        }

    })

}

function refuseSwal(event, title) {

    Swal.fire({
        title: "Refus",
        text: "Êtes-vous certains de vouloir refuser la publication de cet événement : " + title,
        icon: "warning",
        input: "textarea",
        inputLabel: "Raison du refus",
        inputPlaceholder: "Entrez la raison du refus",
        inputAttributes: {
            "class": "form-control"
        },
        confirmButtonText: "Envoyer",
        confirmButtonColor: "#28a745",
        showCancelButton: true,
        cancelButtonText: "Annuler",
        cancelButtonColor: "#dc3545",
        preConfirm: (text) => {
            if (text) {
                refuse(event, text);
            } else {
                Swal.fire({
                    icon: "error",
                    text: "Vous devez entrer une raison de refus",
                    timer: 2000,
                    showConfirmButton: false,
                    didClose: () => {
                        refuseSwal(event, title);
                    }
                });
            }
        }
    })


}
function reasonSwal(reason)
{

    Swal.fire({
        icon: "info",
        title: "En attente de modification",
        input: "textarea",
        inputLabel: "Raison du refus",
        inputValue: reason,
        inputAttributes: {
            "class": "form-control",
            "disabled": "disabled"
        },
        showConfirmButton: false,
        showCloseButton: true
    })

}

function validate(event) {

    $.ajax({
        url: `${validatePath}`,
        method: "POST",
        data: {
            event
        }
    }).then(() => {
        window.location = moderationPath
    })

}

function refuse(event, text) {

    $.ajax({
        url: `${refusePath}`,
        method: "POST",
        data: {
            event,
            text
        }
    }).then(() => {
        window.location = moderationPath
    })

}
