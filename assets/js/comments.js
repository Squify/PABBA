class CommentTutorial {

    constructor(params) {
        self = this
        self.params = params
    }

    edit() {
        $("#updateComment").on("click", (event) => {
            console.log("ok");
            id = event.currentTarget.dataset.id
            url = self.params.urls.getComment.substring(0, self.params.urls.getComment.length - 1);
            $.ajax({
                url: url + str(id), 
                type: "GET",
                success: (data) => {
                    console.log(data)
                },
                complete: () => {
                    console.log("complete")
                }
            });
                // ajax get comment
                // remplir modal
        })

    }

    // modal update Submit
    // prevent default
    // ajax update
    // update tout les commentaires
}