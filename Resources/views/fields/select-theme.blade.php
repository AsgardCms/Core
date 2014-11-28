<div class="form-group">
    <label for="{{ $settingName }}">{{ $moduleInfo['description'] }}</label>
    <select class="form-control" name="{{ $settingName }}" id="{{ $settingName }}">
        <?php foreach($themes as $name => $theme): ?>
            <option value="{{ $name }}" {{ isset($dbSettings[$settingName]) && $dbSettings[$settingName]->plainValue == $name ? 'selected' : '' }}>
                {{ ucfirst($name) }}
            </option>
        <?php endforeach; ?>
    </select>
</div>
