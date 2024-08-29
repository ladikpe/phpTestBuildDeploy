function ToggleMode() {
  var assign_mode = document.getElementById("assign_mode").value;
  if (assign_mode == "employees") {
    document.getElementById("employee_mode").style.display = "block";
    document.getElementById("designation_mode").style.display = "none";
    document.getElementById("group_mode").style.display = "none";
  } else if (assign_mode == "department") {
    document.getElementById("employee_mode").style.display = "none";
    document.getElementById("designation_mode").style.display = "block";
    document.getElementById("group_mode").style.display = "none";
  } else {
    document.getElementById("employee_mode").style.display = "none";
    document.getElementById("designation_mode").style.display = "none";
    document.getElementById("group_mode").style.display = "block";
  }
}
