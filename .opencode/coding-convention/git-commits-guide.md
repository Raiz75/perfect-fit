# Laravel Conventions — Git / Commit Conventions

**Laravel version reference:** 12.x / 13.x (language-agnostic, applies to this codebase)

---

## 1. Branch Naming

```bash
# Correct — type-prefixed, kebab-case, references ticket if applicable
feature/order-refund-flow
fix/order-status-race-condition
chore/upgrade-laravel-12
feature/PROJ-482-add-order-export
```

```bash
# Wrong — no type, unclear scope, personal-name branches
patch1
johns-branch
new-stuff
```

**Rule:** Prefix with the change type (`feature/`, `fix/`, `chore/`, `refactor/`, `hotfix/`), followed by a kebab-case description of the change, optionally including a ticket ID if the team tracks issues externally. Branch names should be understandable without opening the ticket tracker.

---

## 2. Commit Message Format (Conventional Commits)

```bash
# Correct
feat(orders): add refund action for cancelled orders
fix(auth): prevent session fixation on password reset
chore(deps): upgrade laravel/framework to ^12.0
refactor(orders): extract shipping calculation into Action class
docs(readme): document local queue worker setup
```

```bash
# Wrong — vague, no type, no scope
fixed bug
updates
wip
asdf
```

**Rule:** Follow Conventional Commits: `type(scope): short imperative description`.
- **Types**: `feat`, `fix`, `chore`, `refactor`, `docs`, `test`, `perf`, `style`, `ci`.
- **Scope**: the affected module/domain (`orders`, `auth`, `deps`) — omit only if the change is truly cross-cutting.
- **Description**: imperative mood ("add", not "added"/"adds"), no trailing period, under ~72 characters on the summary line.
- Use the commit body (blank line, then free text) for the *why* when the change isn't self-explanatory from the diff — not a restatement of the diff itself.
- Breaking changes: `feat(orders)!: change refund API response shape`, with a `BREAKING CHANGE:` footer explaining the migration.

---

## 3. PR Size/Scope Conventions

```
// Correct — one logical change, reviewable in one sitting
PR: "feat(orders): add refund action for cancelled orders"
  - OrderRefundAction
  - OrderController::refund()
  - RefundOrderRequest
  - migration: add refunded_at to orders
  - tests for the above
```

```
// Wrong — unrelated changes bundled because they happened to land around the same time
PR: "various fixes"
  - refund flow
  - unrelated dependency bump
  - typo fix in an unrelated view
  - new admin dashboard widget
```

**Rule:** One PR = one logical, reviewable unit of work — same principle as "one migration = one logical change." A reviewer should be able to understand the full intent of a PR from its title and description without needing to mentally separate out unrelated changes. Split unrelated fixes/chores into their own PRs, even if small — bundling them "to save time" costs more review time than it saves, and makes `git revert` on a single concern impossible. As a rough guideline, if a PR touches more than ~400-500 lines of non-generated code or spans multiple unrelated domains, consider whether it should be split.

---

## Quick Reference

| Concern | Convention |
|---|---|
| Branch naming | `type/kebab-case-description`, ticket ID if applicable |
| Commit format | Conventional Commits — `type(scope): imperative description` |
| Commit body | Explains *why*, not a restatement of the diff |
| Breaking changes | `type(scope)!:` + `BREAKING CHANGE:` footer |
| PR scope | One logical change per PR; split unrelated fixes into separate PRs |