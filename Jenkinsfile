pipeline {
  agent { 
    docker { 
      image 'cypress/base:10' 
    } 
  }

  stages {
    stage('build and test') {
      environment {
        HOME="."
        CYPRESS_RECORD_KEY = credentials('7c83da05-0a2f-416e-b953-9d3488975693')
      }

      steps {
        sh 'npm ci'
        sh "npm run test:ci:record"
      }
    }
  }
}