/// <reference types="cypress" />
/* eslint-disable no-console */
const globby = require('globby')
const { rmdir } = require('fs')

const { readExcelFile } = require('./read-excel')
const { readPdf } = require('./read-pdf')

/**
 * @type {Cypress.PluginConfig}
 */
module.exports = (on, config) => {
  // `on` is used to hook into various events Cypress emits
  // `config` is the resolved Cypress config

  // register utility tasks to read and parse Excel files
  on('task', {
    readExcelFile,

    readPdf,

    deleteFolder (folderName) {
      console.log('deleting folder %s', folderName)

      return new Promise((resolve, reject) => {
        rmdir(folderName, { maxRetries: 10, recursive: true }, (err) => {
          if (err) {
            console.error(err)

            return reject(err)
          }

          resolve(null)
        })
      })
    },

    // a task to find one file matching the given mask
    // returns just the first matching file
    async findFiles (mask) {
      if (!mask) {
        throw new Error('Missing a file mask to search')
      }

      console.log('searching for files %s', mask)

      const list = await globby(mask)

      if (!list.length) {
        console.log('found no files')

        return null
      }

      console.log('found %d files, first one %s', list.length, list[0])

      return list[0]
    },

  })
}