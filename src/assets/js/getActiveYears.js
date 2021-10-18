function getActiveYears() {
  return new Date().getFullYear() - 1998;
}
document.getElementById("getYears").innerHTML = getActiveYears();