<h3 class="header">權限管理</h3>
<div class="ui fluid vertical buttons">
    <a
        href="{{ route('projects.members.index', $project->id) }}"
        class="ui @if (preg_match('/projects\.(members).*/', request()->route()->getName())) active @endif button"
    >
        專案成員管理
    </a>
</div>

