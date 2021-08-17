describe('Homepage tests', () => {
    const email = "info@protechas.com";
    const password = "InN0V81V06";
    const test_dept = "Unassigned";

    beforeEach(() => {
        cy.login(email, password);
    })
    afterEach(() => {
        cy.visit('/logout');
    })

    it('checks homepage elements and functions', () => {

        cy.get('canvas[id=lineChart]')
        .should('exist');

        cy.get('canvas[id=doughnutChart]')
        .should('exist');

        cy.get('canvas[id=barChart]')
        .should('exist');

        cy.get('table').within(() => {
            cy.get('tr')
            .should('exist');
        })

        // cy.get('select[name=member_dept]')
        // .should('have.value', '0')

        cy.get('dd[class$=text-right]').eq(0)
            .invoke('text')
            .then((text1) => {
                cy.get('select[name=member_dept]')
                    .select('Unassigned')
                    .should('have.value', '1133'); //antipattern

                cy.wait(1000)

                cy.get('dd[class$=text-right]').eq(0)
                    .invoke('text')
                    .then((text2) => {
                        expect(text1).not.to.eq(text2);
                    })
            })
    })
})