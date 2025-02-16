document.addEventListener("DOMContentLoaded", () => {
  const profileBtn = document.querySelector(".profile-btn")
  const dropdownContent = document.querySelector(".dropdown-content")
  const modal = document.getElementById("password-modal")
  const changePasswordBtn = document.getElementById("change-password-btn")
  const closeBtn = document.querySelector(".close")
  const changePasswordForm = document.getElementById("change-password-form")
  const fileInput = document.getElementById("file")
  const uploadForm = document.getElementById("upload-form")
  const imageNameInput = document.getElementById("image_name")
  const profileImage = document.getElementById("profile-image")
  const editProfileBtn = document.getElementById("edit-profile-btn")
  const editProfileModal = document.getElementById("edit-profile-modal")
  const editProfileCloseBtn = editProfileModal.querySelector(".close")

  profileBtn.addEventListener("click", (e) => {
    e.stopPropagation()
    dropdownContent.classList.toggle("show")
  })

  document.addEventListener("click", (e) => {
    if (!e.target.matches(".profile-btn")) {
      if (dropdownContent.classList.contains("show")) {
        dropdownContent.classList.remove("show")
      }
    }
  })

  changePasswordBtn.addEventListener("click", (e) => {
    e.preventDefault()
    modal.style.display = "block"
    dropdownContent.classList.remove("show")
  })

  closeBtn.addEventListener("click", () => {
    modal.style.display = "none"
  })

  changePasswordForm.addEventListener("submit", (e) => {
    e.preventDefault()
    const currentPassword = document.getElementById("current-password").value
    const newPassword = document.getElementById("new-password").value
    const confirmPassword = document.getElementById("confirm-password").value

    if (newPassword !== confirmPassword) {
      alert("New passwords do not match!")
      return
    }

    // Create a form element to submit the data
    const form = document.createElement("form")
    form.method = "POST"
    form.action = "change_password.php"

    // Append form data
    const currentPasswordInput = document.createElement("input")
    currentPasswordInput.type = "hidden"
    currentPasswordInput.name = "currentPassword"
    currentPasswordInput.value = currentPassword
    form.appendChild(currentPasswordInput)

    const newPasswordInput = document.createElement("input")
    newPasswordInput.type = "hidden"
    newPasswordInput.name = "newPassword"
    newPasswordInput.value = newPassword
    form.appendChild(newPasswordInput)

    document.body.appendChild(form)
    form.submit()
  })

  profileImage.addEventListener("click", () => {
    fileInput.click()
  })

  fileInput.addEventListener("change", function () {
    if (this.files && this.files[0]) {
      // Optionally, display the selected image before uploading
      const reader = new FileReader()
      reader.onload = function (e) {
        profileImage.src = e.target.result
      }
      reader.readAsDataURL(this.files[0])
    }
  })

  editProfileBtn.addEventListener("click", () => {
    editProfileModal.style.display = "block"
  })

  editProfileCloseBtn.addEventListener("click", () => {
    editProfileModal.style.display = "none"
  })
})

