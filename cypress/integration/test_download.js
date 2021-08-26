import {
    validateExcelFile,
    // validateBinaryFile, 
    deleteDownloadsFolder,
} from './utils'
const path = require('path')

describe('file download', () => {
    const email = "info@protechas.com"
    const password = "InN0V81V06"
    const downloadsFolder = Cypress.config('downloadsFolder')

    // should we delete all the files in the downloads folder
    // before each test?
    // beforeEach(deleteDownloadsFolder)
    beforeEach(() => {
        cy.login(email, password)
        deleteDownloadsFolder()
    })

    it('Excel file', () => {
        // let's download a binary file
  
        cy.visit('/vehicles')
        // cy.contains('button[title=Export to Excel]'
        var vehcile_num
        cy.get('div[role=status]').then(($div) => {
            vehcile_num = $div.text()
        })

        // var vehcile_num = cy.get('div[role=status]').invoke('text')
        cy.log(vehcile_num)
        cy.get('button[class^=dt-button]').eq(0).click()
  
        cy.log('**confirm downloaded file**')
  
        validateExcelFile()
      })

})