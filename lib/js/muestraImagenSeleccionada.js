import { exportaAHtml } from "./exportaAHtml.js"
import { getImgParaElementoHtml } from "./muestraObjeto.js"

/**
 * @param { Document | HTMLElement } raizHtml
 * @param {HTMLInputElement} input
 */
export function muestraImagenSeleccionada(raizHtml, input) {
 return new Promise((resolve, reject) => {
  setTimeout(() => {

   const img = getImgParaElementoHtml(raizHtml, input)
   if (img !== null) {
    try {

     const dataUrl = getDataUrlDeSeleccion(input)
     if (dataUrl === "") {

      const file = input.dataset.file
      if (file === undefined || file === "") {
       img.hidden = true
       img.src = ""
      } else {
       img.hidden = false
       img.src = file
      }

     } else {

      img.hidden = false
      img.src = dataUrl

     }

     resolve(true)

    } catch (error) {

     img.hidden = true

     reject(error)

    }
   }

  },
   500)
 })
}
exportaAHtml(muestraImagenSeleccionada)

/**
 * @param {HTMLInputElement} input
 */
export function getDataUrlDeSeleccion(input) {
 const seleccion = getArchivoSeleccionado(input)
 if (seleccion === null) {
  return ""
 } else {
  return URL.createObjectURL(seleccion)
 }
}
exportaAHtml(getDataUrlDeSeleccion)


/**
 * @param { HTMLInputElement } input
 */
export function getArchivoSeleccionado(input) {
 const seleccion = input.files
 if (seleccion === null || seleccion.length === 0) {
  return null
 } else {
  return seleccion.item(0)
 }
}
exportaAHtml(getArchivoSeleccionado)