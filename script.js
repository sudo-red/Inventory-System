$(document).ready(function() {
  $(".increment").on("click", function() {
    var row = $(this).closest("tr");
    var counterInput = row.find(".counterValue");
    var currentValue = parseInt(counterInput.val()) || 1;
    
    // Check if the current value is not a number or empty, set it to 0
    if (isNaN(currentValue) || currentValue === "") {
      currentValue = 1;
    }
    
    var newValue = currentValue + 1;
    var id = $(this).data("id");

    counterInput.val(newValue);
    updateCounterInDatabase(id, newValue);
  });

  $(".decrement").on("click", function() {
    var row = $(this).closest("tr");
    var counterInput = row.find(".counterValue");
    var currentValue = parseInt(counterInput.val()) || 1;
    
    // Check if the current value is not a number or empty, set it to 0
    if (isNaN(currentValue) || currentValue === "") {
      currentValue = 1;
    }
    
    // Check if currentValue is already 1 or less, if so, return
    if (currentValue <= 1) {
      return;
    }
    
    var newValue = currentValue - 1;
    var id = $(this).data("id");

    counterInput.val(newValue);
    updateCounterInDatabase(id, newValue);
  });

  $(".counterValue").on("change", function() {
    var row = $(this).closest("tr");
    var counterInput = $(this);
    var newValue = parseInt(counterInput.val()) || 1;
    var id = row.find(".increment").data("id");
    
    // Check if the new value is negative, if so, set it to 0
    if (newValue <= 0) {
      newValue = 1;
      counterInput.val(newValue);
    }

    updateCounterInDatabase(id, newValue);
  });


  function updateCounterInDatabase(id, value) {
    // Make an AJAX request to update the value in the database
    $.ajax({
      url: "update_counter.php",
      method: "POST",
      data: { id: id, quantity: value },
      success: function(response) {
        console.log("Counter updated successfully!");
      },
      error: function(xhr, status, error) {
        console.error("Error updating counter:", error);
      }
    });
  }
});
