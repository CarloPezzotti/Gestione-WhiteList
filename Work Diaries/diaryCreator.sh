date=$(date '+%d%m%Y')
name="diario${date}"
mkdir $name
cd $name
cp "../diarioTemplate.md" "${name}.md"
code "${name}.md"