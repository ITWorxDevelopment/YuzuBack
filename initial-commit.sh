#!/usr/bin/env bash
set -Eeuo pipefail

die() {
    printf 'Error: %s\n' "$*" >&2
    exit 1
}

repo_root=$(git rev-parse --show-toplevel 2>/dev/null) || die 'Run this script inside a Git repository.'
cd "$repo_root"

branch=$(git symbolic-ref --quiet --short HEAD) || die 'HEAD is detached; check out a branch first.'
git check-ref-format --branch "$branch" >/dev/null || die 'The active branch name is invalid.'

remote=$(git config --get "branch.${branch}.remote" || true)
merge_ref=$(git config --get "branch.${branch}.merge" || true)

if [[ -n "$remote" && "$remote" != '.' ]]; then
    [[ "$merge_ref" == refs/heads/* ]] || die "The upstream for '$branch' is not a remote branch."
    remote_branch=${merge_ref#refs/heads/}
else
    remote=origin
    remote_branch=$branch
fi

git remote get-url "$remote" >/dev/null 2>&1 || die "Remote '$remote' is not configured. Set an upstream or configure origin."
remote_ref="refs/heads/$remote_branch"
git check-ref-format "$remote_ref" >/dev/null || die 'The target remote branch name is invalid.'
local_tracking_ref="refs/remotes/$remote/$remote_branch"
git check-ref-format "$local_tracking_ref" >/dev/null || die 'The local remote-tracking ref is invalid.'

printf 'This will replace the commit history for local branch %s and %s/%s.\n' "$branch" "$remote" "$remote_branch"
printf 'Other local/remote branches and tags are not deleted and may still retain old history.\n'
printf 'The current working tree, including staged, unstaged, and untracked non-ignored files, will be committed.\n\n'

read -r -p 'New commit message: ' commit_message
[[ -n "${commit_message//[[:space:]]/}" ]] || die 'The commit message cannot be empty.'

printf '\nTo proceed, type OVERWRITE %s: ' "$branch"
read -r confirmation
[[ "$confirmation" == "OVERWRITE $branch" ]] || die 'Confirmation did not match; no changes were made.'

old_commit=$(git rev-parse "refs/heads/$branch")
old_tracking=$(git rev-parse --verify "$local_tracking_ref" 2>/dev/null || true)
git add -A
tree=$(git write-tree)
new_commit=$(printf '%s\n' "$commit_message" | git commit-tree "$tree")

expected_remote=$(git ls-remote --heads "$remote" "$remote_ref" | awk -v ref="$remote_ref" '$2 == ref { print $1; exit }')
if [[ -n "$expected_remote" ]]; then
    printf '\nRemote branch exists; it will be force-updated.\n'
else
    printf '\nRemote branch does not exist; it will be created.\n'
fi

git push "--force-with-lease=$remote_ref:$expected_remote" "$remote" "$new_commit:$remote_ref"
git update-ref "refs/heads/$branch" "$new_commit" "$old_commit"
current_tracking=$(git rev-parse --verify "$local_tracking_ref" 2>/dev/null || true)
if [[ "$current_tracking" == "$new_commit" ]]; then
    :
elif [[ -n "$old_tracking" && "$current_tracking" == "$old_tracking" ]]; then
    git update-ref "$local_tracking_ref" "$new_commit" "$old_tracking"
elif [[ -z "$current_tracking" ]]; then
    git update-ref "$local_tracking_ref" "$new_commit"
else
    die "Local tracking ref '$local_tracking_ref' changed unexpectedly after the push."
fi
git reset --hard "$new_commit"

git reflog expire --expire=now --all
git gc --prune=now

printf '\nCreated initial commit %s on %s and pushed it to %s/%s.\n' "$new_commit" "$branch" "$remote" "$remote_branch"