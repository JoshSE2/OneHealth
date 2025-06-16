// modal handlers
function openModal(id) {
  document.getElementById(id).style.display = 'block';
}

function closeModal(id) {
  document.getElementById(id).style.display = 'none';
}

// AJAX for Edit
const editForm = document.getElementById('editForm');
editForm.addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(editForm);

  fetch('../php/edit_doctor_ajax.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(data => {
    document.getElementById('editMsg').innerText = data;
  });
});

// AJAX for Delete
function deleteAccount() {
  fetch('../php/delete_doctor_ajax.php', {
    method: 'POST'
  })
  .then(res => res.text())
  .then(data => {
    document.getElementById('deleteMsg').innerText = data;
    if (data.includes("deleted")) {
      setTimeout(() => window.location.href = '../logout.php', 2000);
    }
  });
}

// for the patient

function openModal(id) {
    document.getElementById(id).style.display = "block";
}

function closeModal(id) {
    document.getElementById(id).style.display = "none";
}

function deleteAccount() {
    if (!confirm("Are you sure you want to delete your account?")) return;

    fetch("delete_patient.php", {
        method: "POST"
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        if (data.includes("successfully")) {
            window.location.href = "../login.php";
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
}

