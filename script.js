document.addEventListener("DOMContentLoaded", () => {
    const bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
    const riskyDiseases = ['Anemia', 'Hypertension'];
    const donationLimit = 500;
  
    function alertErrors(errors) {
      if (errors.length) {
        alert("Please fix the following errors:\n\n" + errors.join('\n'));
        return false;
      }
      return true;
    }
  
    function isValidPhone(phone) {
      return /^[6-9]\d{9}$/.test(phone);
    }
  
    function allowOnlyDigits(input) {
      input.addEventListener("input", () => {
        input.value = input.value.replace(/\D/g, '');
      });
    }
  
    document.querySelectorAll('#phone, #contact').forEach(field => {
      if (field) allowOnlyDigits(field);
    });
  
    // Donor Registration Form
    const donorForm = document.querySelector('form[action="add-donor.php"]');
    if (donorForm) {
      donorForm.addEventListener("submit", (e) => {
        const errors = [];
        const name = document.getElementById("name")?.value.trim();
        const age = parseInt(document.getElementById("age")?.value, 10);
        const gender = document.getElementById("gender")?.value;
        const bloodGroup = document.getElementById("blood_group")?.value;
        const contact = document.getElementById("phone")?.value.trim();
        const address = document.getElementById("address")?.value.trim();
        const quantity = parseInt(document.getElementById("quantity")?.value || "0", 10);
        const disease = document.getElementById("disease")?.value.trim() || "";
  
        if (!name) errors.push("Full Name is required.");
        if (!age || age < 18 || age > 65) errors.push("Age must be between 18 and 65.");
        if (!gender) errors.push("Gender must be selected.");
        if (!bloodGroups.includes(bloodGroup)) errors.push("Valid blood group must be selected.");
        if (!isValidPhone(contact)) errors.push("Contact number must be valid (10 digits starting with 6-9).");
        if (!address) errors.push("Address is required.");
        if (!quantity || quantity < 1 || quantity > donationLimit) {
          errors.push(`Quantity must be between 1 and ${donationLimit} ml.`);
        }
        if (riskyDiseases.includes(disease)) {
          errors.push("This disease disqualifies the donor.");
        }
  
        if (!alertErrors(errors)) e.preventDefault();
      });
    }
  
    // Patient Registration Form
    const patientForm = document.querySelector('form[action="add-patient.php"]');
    if (patientForm) {
      patientForm.addEventListener("submit", (e) => {
        const errors = [];
        const name = document.getElementById("name")?.value.trim();
        const age = parseInt(document.getElementById("age")?.value || "0", 10);
        const gender = document.getElementById("gender")?.value;
        const bloodGroup = document.getElementById("blood_group")?.value;
        const contact = document.getElementById("phone")?.value.trim();
        const address = document.getElementById("address")?.value.trim();
  
        if (!name) errors.push("Name is required.");
        if (!age || age < 1 || age > 120) errors.push("Age must be between 1 and 120.");
        if (!gender) errors.push("Gender must be selected.");
        if (!bloodGroups.includes(bloodGroup)) errors.push("Valid blood group must be selected.");
        if (contact && !isValidPhone(contact)) errors.push("Phone number must be valid.");
        if (!address) errors.push("Address is required.");
  
        if (!alertErrors(errors)) e.preventDefault();
      });
    }
  
    // Hospital Blood Request Form
    const hospitalForm = document.querySelector('form[action="submit-request.php"]');
    if (hospitalForm) {
      hospitalForm.addEventListener("submit", (e) => {
        const errors = [];
        const hospital = document.getElementById("hospital_name")?.value.trim();
        const contactPerson = document.getElementById("contact_person")?.value.trim();
        const contact = document.getElementById("phone")?.value.trim();
        const bloodGroup = document.getElementById("blood_group")?.value;
        const units = parseInt(document.getElementById("units")?.value.trim() || "0", 10);
        const reason = document.getElementById("reason")?.value.trim();
  
        if (!hospital) errors.push("Hospital Name is required.");
        if (!contactPerson) errors.push("Contact Person is required.");
        if (!isValidPhone(contact)) errors.push("Contact Number must be valid.");
        if (!bloodGroups.includes(bloodGroup)) errors.push("Valid blood group must be selected.");
        if (!units || units <= 0) errors.push("Units must be a positive number.");
        if (!reason) errors.push("Reason or remarks are required.");
  
        if (!alertErrors(errors)) e.preventDefault();
      });
    }
  
    // Contact Us Form
    const contactForm = document.querySelector('form[action="send-message.php"]');
    if (contactForm) {
      contactForm.addEventListener("submit", (e) => {
        const errors = [];
        const name = document.getElementById("name")?.value.trim();
        const email = document.getElementById("email")?.value.trim();
        const subject = document.getElementById("subject")?.value.trim();
        const message = document.getElementById("message")?.value.trim();
  
        if (!name) errors.push("Name is required.");
        if (!email || !email.includes("@")) errors.push("Valid email is required.");
        if (!subject) errors.push("Subject is required.");
        if (!message) errors.push("Message cannot be empty.");
  
        if (!alertErrors(errors)) e.preventDefault();
      });
    }
  
    // Blood Entry Form
    const bloodEntryForm = document.querySelector('form[action="add-blood.php"]');
    if (bloodEntryForm) {
      bloodEntryForm.addEventListener("submit", (e) => {
        const errors = [];
        const bloodGroup = document.getElementById("blood_group")?.value;
        const units = parseInt(document.getElementById("units")?.value || "0", 10);
        const source = document.getElementById("source")?.value;
        const date = document.getElementById("entry_date")?.value;
  
        if (!bloodGroups.includes(bloodGroup)) errors.push("Valid blood group is required.");
        if (!units || units <= 0) errors.push("Units must be a positive number.");
        if (!source) errors.push("Source must be selected.");
        if (!date) errors.push("Date is required.");
  
        if (!alertErrors(errors)) e.preventDefault();
      });
    }
  
    // Highlight low stock units in stock table
    document.querySelectorAll(".table-section table tbody tr").forEach(row => {
      const units = parseInt(row.children[1]?.textContent || "0", 10);
      if (units < 5) {
        row.children[1].style.color = "red";
        row.children[1].style.fontWeight = "bold";
      }
    });
  
    // Global Search Bar Logic (Home and other pages)
    const searchInput = document.querySelector(".search-bar input");
    const searchBtn = document.querySelector(".search-bar button");
    if (searchInput && searchBtn) {
      searchBtn.addEventListener("click", () => {
        const query = searchInput.value.trim();
        alert(query ? `Searching for: "${query}"...` : "Please enter a keyword to search.");
      });
    }
  
    // Animate recent activity list if present
    const recentItems = document.querySelectorAll(".recent-activity li");
    recentItems.forEach((item, i) => {
      item.style.opacity = "0";
      setTimeout(() => {
        item.style.transition = "opacity 0.8s ease-in";
        item.style.opacity = "1";
      }, i * 300);
    });
  });
  