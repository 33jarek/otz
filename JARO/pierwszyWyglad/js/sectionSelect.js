const selectKiller = document.querySelector("#selectKiller");
const selectSurvivor = document.querySelector("#selectSurvivor");

const sectionKiller = document.querySelector("#sectionKiller");
const sectionSurvivor = document.querySelector("#sectionSurvivor");

selectKiller.addEventListener("click", ()=>{
    sectionKiller.style.display="block"
    sectionSurvivor.style.display="none"
})
selectSurvivor.addEventListener("click", ()=>{
    sectionKiller.style.display="none"
    sectionSurvivor.style.display="block"
})