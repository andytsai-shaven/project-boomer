var $ = window.$ = window.jQuery = require('jquery')
window.raphael = require('raphael')
window.flowchart = require('./libs/flowchart.min')
require('semantic-ui/dist/semantic.min')

var React = require('react')
var ReactDOM = require('react-dom')
var WorkflowList = require('./components/workflow-list')
var WorkflowNodeList = require('./components/workflow-node-list')
var FlowtypeSelect = require('./components/flowtype-select')
var UnitSelect = require('./components/unit-select')
var CostTypeSelect = require('./components/cost-type-select')
var WorkItemList = require('./components/work-item-list')
var WorkflowSelect = require('./components/workflow-select')
var WorkSelect = require('./components/work-select')
var ProjectWorkList = require('./components/project-work-list')

if ($('#workflow-list').length) {
    ReactDOM.render(
      <WorkflowList />,
      document.getElementById('workflow-list')
    )
}

if ($('#workflow-node-list').length) {
    var workflowId = parseInt($('#workflow-node-list').attr('data-workflow-id'))

    ReactDOM.render(
      <WorkflowNodeList workflowId={ workflowId } />,
      document.getElementById('workflow-node-list')
    )
}

if ($('#flowtype-select').length) {
    ReactDOM.render(
        <FlowtypeSelect />,
        document.getElementById('flowtype-select')
    )
}

if ($('#unit-select').length) {
    ReactDOM.render(
        <UnitSelect />,
        document.getElementById('unit-select')
    )
}

if ($('#cost-type-select').length) {
    ReactDOM.render(
        <CostTypeSelect />,
        document.getElementById('cost-type-select')
    )
}

if ($('#work-item-list').length) {
    var workId = parseInt($('#work-item-list').attr('data-work-id'))

    ReactDOM.render(
        <WorkItemList workId={workId}/>,
        document.getElementById('work-item-list')
    )
}

if ($('#workflow-select').length) {
    ReactDOM.render(
        <WorkflowSelect />,
        document.getElementById('workflow-select')
    )
}

if ($('#work-select').length) {
    ReactDOM.render(
        <WorkSelect />,
        document.getElementById('work-select')
    )
}

if ($('#project-work-list').length) {
    var projectId = parseInt($('#project-work-list').attr('data-project-id'))

    ReactDOM.render(
        <ProjectWorkList projectId={projectId} />,
        document.getElementById('project-work-list')
    )
}
