import {
    validateExcelFile,
    deleteDownloadsFolder,
} from './utils'
const path = require('path')

describe('Vehicles page tests', () => {
    const email = "info@protechas.com";
    const password = "InN0V81V06";

    beforeEach(() => {
        cy.login(email, password);
        deleteDownloadsFolder();
    })
    afterEach(() => {
        cy.visit('/logout');
    })
    
    // it('Downloads excel file', () => {
    //     cy.get('[class$=text-right]').then(($dd) => {
    //         var vehicles = $dd.eq(1).text();
    //         vehicles = parseInt(vehicles)+1;

    //         cy.visit('/vehicles');
    //         cy.table_exists();

    //         cy.get('button[class^=dt-button]').eq(0).click()

    //         cy.log('**confirm downloaded file**')

    //         validateExcelFile(vehicles)
    //     })
    // })

    it('Checks table exists', () => {
        cy.visit('/vehicles');
        cy.table_exists();
    })

    it('Checks total vehicles in dashboard reflects here', () => {

        cy.get('[class$=text-right]').then(($dd) => {
            const vehicles = $dd.eq(1).text();

            cy.visit('/vehicles');

            cy.get('div[role=status]').within(() => {
                cy.contains(vehicles)
                    .should('exist');
            })
        })
    })

    it('Test table length function', () => {
        cy.visit('/vehicles');
        cy.test_table_length();
    })

    it('Test table navigation function', () => {
        cy.visit('/vehicles');
        cy.test_page_nav();
    })

    it('Test adding single vehicle with empty fields', () => {
        cy.visit('/vehicles');
        cy.get('button').contains('Vehicles').click();
        cy.get('form[id$=vehicle]').should('be.visible');
        cy.get('button').contains('Save').click();
        cy.get('div[class$=danger]').should('be.visible');
    })

    it('Test adding single vehicle', () => {
        cy.visit('/vehicles');
        cy.get('button').contains('Vehicles').click();
        cy.add_dummy_vehicle();
    })
})