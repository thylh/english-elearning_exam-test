# Spec to Deploy Workflow

## Purpose

Use this workflow for any change that should move from idea to release with explicit gates and evidence at each step.

## Flow

SPEC -> PLAN -> TASK BREAKDOWN -> TDD TESTS -> IMPLEMENT -> VERIFY -> REFLECT -> REFACTOR -> COMMIT -> DEPLOY

## Phase Guide

### SPEC

Define the problem, success criteria, constraints, and out-of-scope items.

Use Superpowers: `brainstorming`.

Exit when the goal is testable and the scope is clear.

### PLAN

Turn the spec into a concrete implementation plan with exact files, tests, and verification commands.

Use Superpowers: `writing-plans`.

Exit when the plan is small, actionable, and has no placeholders.

### TASK BREAKDOWN

Break the plan into small tasks that can be completed independently.

Use Superpowers: `subagent-driven-development` for independent tasks or `executing-plans` for sequential work.

Exit when each task has a clear done condition.

### TDD TESTS

Write the failing test first and verify it fails for the right reason.

Use Superpowers: `test-driven-development`.

Exit when the red step is proven.

### IMPLEMENT

Write the minimum code needed to make the test pass.

Exit when the target test passes and scope has not widened.

### VERIFY

Run the narrowest meaningful checks, then expand only if needed.

Use Superpowers: `verification-before-completion`.

Exit when tests and relevant checks pass with fresh evidence.

### REFLECT

Compare the implementation against the spec and note any gaps or assumptions.

Exit when the result matches the original requirements or the requirements are updated.

### REFACTOR

Clean up names, duplication, and structure without changing behavior.

Exit when the code is simpler and the tests still pass.

### COMMIT

Commit the verified work in a focused change set.

Exit when the diff is intentional and reviewable.

### DEPLOY

Finish the branch lifecycle: merge locally, create a PR, keep the branch, or discard it.

Use Superpowers: `finishing-a-development-branch`.

Exit when the branch state is intentional and recorded.
