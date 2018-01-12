/* Chris Sciavolino (cds253@cornell.edu) */
//// vanilla javascript version
// document.addEventListener('DOMContentLoaded', function () {
//     // grab remove buttons
//     const RM_BUTTONS = document.querySelectorAll('.js-remove-button');
//     const removeInput = function (inputElement) {
//             const SELECTED_INPUT = inputElement.parentNode;
//             SELECTED_INPUT.remove();
//     }

//     // when any remove button clicked, remove the input field
//     for (let i = 0; i < RM_BUTTONS.length; i++) {
//         RM_BUTTONS[i].addEventListener('click', function () { removeInput(this) });
//     }

//     // when add button clicked, add new input div
//     const ADD_BUTTON = document.querySelector('.js-add-button');
//     ADD_BUTTON.addEventListener('click', function () {
//         // create input div <div></div>
//         const NEW_DIV_ITEM = document.createElement('div');

//         // create input item <input type='text' name='inputs[]' required />
//         const NEW_INPUT_ITEM = document.createElement('input');
//         NEW_INPUT_ITEM.type = 'text';
//         NEW_INPUT_ITEM.name = 'inputs[]';
//         NEW_INPUT_ITEM.required = true;

//         // create rm button <button class='js-remove-button'>Remove X</button>
//         const NEW_RM_BTN = document.createElement('button');
//         NEW_RM_BTN.className = 'js-remove-button';
//         NEW_RM_BTN.innerText = 'Remove X';
//         NEW_RM_BTN.type = 'button';

//         // add same event listener to new button
//         NEW_RM_BTN.addEventListener('click', function () { removeInput(this) });

//         // add input item and remove button inside div element
//         NEW_DIV_ITEM.appendChild(NEW_INPUT_ITEM);
//         NEW_DIV_ITEM.appendChild(NEW_RM_BTN);

//         // add new div element to the inputs container
//         const INPUTS_CONTAINER = document.querySelector('.js-inputs-container');
//         INPUTS_CONTAINER.appendChild(NEW_DIV_ITEM);
//     });
// });

// jQuery version
$(document).ready(function () {
    // set up remove input event listener for all remove buttons
    $('.js-remove-button').on('click', function () {
        this.parentNode.remove();
    });

    // add event listener for add button to add inputs
    $('.js-add-button').on('click', function () {
        // append a single item
        $('.js-inputs-container').append(
            '<div class = "entry input-group col-xs-12"><input class = "form-control" type="text" name="responses[]" placeholder = "Response" required/><span class = "input-group-btn"><button class=" btn btn-danger js-remove-button" type="button"><span class = "glyphicon glyphicon-minus"></span></button></span></div>'
        );

        // add event listener for each of the remove buttons again
        // could optimize to only add event listener for this new button rather than all of them
        $('.js-remove-button').on('click', function () {
            this.parentNode.parentNode.remove();
        });
    });
});