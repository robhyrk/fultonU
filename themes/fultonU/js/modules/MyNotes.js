import $ from 'jquery';

class MyNotes {
    constructor() {
        this.events();
    }
    events() {
        $('#my-notes').on("click", ".delete-note" ,this.deleteNote)
        $('#my-notes').on("click", ".edit-note", this.editNote.bind(this))
        $('#my-notes').on("click", ".update-note", this.updateNote.bind(this))
        $('.submit-note').on("click", this.createNote.bind(this))
    }

    editNote(e) {
        const thisNote = $(e.target).parents("li")
        thisNote.data("state") === 'editable' ? this.makeNoteReadOnly(thisNote) : this.makeNoteEditable(thisNote)
    }

    makeNoteEditable(thisNote) {
        thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i> Cancel')
        thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field")
        thisNote.find(".update-note").addClass("update-note--visible")
        thisNote.data("state", "editable")
    }

    makeNoteReadOnly(thisNote) {
        thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit')
        thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field")
        thisNote.find(".update-note").removeClass("update-note--visible")
        thisNote.data("state", "cancel")

    }
   
    deleteNote(e) {
        const thisNote = $(e.target).parents("li")
        $.ajax({
            url: uni_data.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
            type: 'DELETE',
            beforeSend: (xhr) => {
              xhr.setRequestHeader('X-WP-NONCE', uni_data.nonce)  
            },
            success: (response) => {
                thisNote.slideUp();
                console.log(response)
                if(response.userNoteCount < 4){
                    $(".note-limit-message").removeClass("active");
                }
            },
            error: (response) => {
                console.log("Sorry: " + response)
            }
        })
    }
    updateNote(e) {
        const thisNote = $(e.target).parents("li")

        const updatedPost = {
            'title': thisNote.find(".note-title-field").val(),
            'content': thisNote.find(".note-body-field").val()
        }
        $.ajax({
            url: uni_data.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
            type: 'POST',
            data: updatedPost,
            beforeSend: (xhr) => {
              xhr.setRequestHeader('X-WP-NONCE', uni_data.nonce)  
            },
            success: (response) => {
                this.makeNoteReadOnly(thisNote)
                console.log(response)
            },
            error: (response) => {
                console.log("Sorry: " + response)
            }
        })
    }

    createNote(e) {
        const newPost = {
            'title': $(".new-note-title").val(),
            'content': $(".new-note-body").val(),
        }
        $.ajax({
            url: uni_data.root_url + '/wp-json/wp/v2/note/',
            type: 'POST',
            data: newPost,
            beforeSend: (xhr) => {
              xhr.setRequestHeader('X-WP-NONCE', uni_data.nonce)  
            },
            success: (response) => {
                $(".new-note-title, .new-note-body").val('')
                $(`
                <li data-id="${response.id}">
                        <input readonly class="note-title-field" value="${response.title.raw}">
                        <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i>Edit</span>
                        <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</span>
                        <textarea readonly class="note-body-field">${response.content.raw}
                        </textarea>
                        <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
                    </li>
                `).prependTo("#my-notes").hide().slideDown()
                console.log(response)
            },
            error: (response) => {
                if(response.responsText = "You have reached your note limit.") {
                    $(".note-limit-message").addClass("active");    
                }
                    console.log(response)
                }
        })
    }
}

export default MyNotes;