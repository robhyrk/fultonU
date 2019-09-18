import $ from 'jquery';

class MyNotes {
    constructor() {
        this.events();
    }
    events() {
        $('.delete-note').on("click", this.deleteNote)
        $('.edit-note').on("click", this.editNote.bind(this))
        $('.update-note').on("click", this.updateNote.bind(this))
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
}

export default MyNotes;