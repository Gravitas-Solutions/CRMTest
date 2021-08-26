const path = require('path')

/**
 * Delete the downloads folder to make sure the test has "clean"
 * slate before starting.
 */
export const deleteDownloadsFolder = () => {
  const downloadsFolder = Cypress.config('downloadsFolder')

  cy.task('deleteFolder', downloadsFolder)
}

export const validateExcelFile = (record) => {
  const downloadsFolder = Cypress.config('downloadsFolder')
  const downloadedFilename = path.join(downloadsFolder, 'Innovative Toll.xlsx')

  // ensure the file has been saved before trying to parse it
  cy.readFile(downloadedFilename, 'binary', { timeout: 15000 })
  .should((buffer) => {
    // by having length assertion we ensure the file has text
    // since we don't know when the browser finishes writing it to disk

    // Tip: use expect() form to avoid dumping binary contents
    // of the buffer into the Command Log
    expect(buffer.length).to.be.gt(100)
  })

  cy.log('**the file exists**')

  // the first utility library we use to parse Excel files
  // only works in Node, thus we can read and parse
  // the downloaded file using cy.task
  cy.task('readExcelFile', downloadedFilename)
  // returns an array of lines read from Excel file
  .should('have.length', record)
  .then((list) => {
    expect(list[0], 'header line').to.deep.equal([
      'First name', 'Last name', 'Occupation', 'Age', 'City', 'State',
    ])

    expect(list[1], 'first person').to.deep.equal([
      'Joe', 'Smith', 'student', 20, 'Boston', 'MA',
    ])
  })
}

export const downloadByClicking = (url, name) => {
  cy.log(`about to download **${name}**`)
  cy.document().then((doc) => {
    const link = doc.createElement('a')

    link.href = url
    link.download = name
    link.click()
  })
}

/**
 * Checks if the downloaded folder has file with the given name
 * and the given size in bytes.
 * @param {string} filename The downloaded file name
 * @param {number} expectedSize Expected binary file size in bytes
 */
export const validateBinaryFile = (filename, expectedSize) => {
  expect(filename, 'filename').to.be.a('string')
  expect(expectedSize, 'file size').to.be.a('number').and.be.gt(0)

  const downloadsFolder = Cypress.config('downloadsFolder')
  const downloadedFilename = path.join(downloadsFolder, filename)

  // for now just check the file size
  cy.readFile(downloadedFilename, 'binary', { timeout: 15000 })
  .should((buffer) => {
    // avoid logging the binary data into Command Log
    if (buffer.length !== expectedSize) {
      throw new Error(`File size ${buffer.length} is not ${expectedSize}`)
    }
  })
}