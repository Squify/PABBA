$(function () {

    $("#itemFilters #state").on("change", () => {

        selectAndReplace(
            $("#itemFilters #state").val(),
            $("#itemFilters #toolType").val(),
            $("#itemFilters #name").val()
        )
    })

    $("#itemFilters #toolType").on("change", () => {

        selectAndReplace(
            $("#itemFilters #state").val(),
            $("#itemFilters #toolType").val(),
            $("#itemFilters #name").val()
        )

    })
    
    $("#itemFilters #name").on("change", () => {

        selectAndReplace(
            $("#itemFilters #state").val(),
            $("#itemFilters #toolType").val(),
            $("#itemFilters #name").val()
        )

    })

})

function selectAndReplace(state, toolType, name) {
    // console.log(state);
    // console.log(toolType);
    // console.log(name);

    $.ajax({
        url: pathToItemIndex,
        method: "get",
        data: {
            state,
            toolType,
            name
        },
        success: function(html){

            $("#items").replaceWith(
                $(html).find("#items")
            )

        }
    })

}