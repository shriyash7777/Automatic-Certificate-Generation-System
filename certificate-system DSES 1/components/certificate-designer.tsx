"use client"

import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Switch } from "@/components/ui/switch"
import { Separator } from "@/components/ui/separator"
import type { CertificateConfig } from "@/app/page"

interface CertificateDesignerProps {
  config: CertificateConfig
  onConfigChange: (updates: Partial<CertificateConfig>) => void
  activeTab: string
}

export function CertificateDesigner({ config, onConfigChange, activeTab }: CertificateDesignerProps) {
  const handleInputChange = (field: string, value: string) => {
    const keys = field.split(".")
    if (keys.length === 1) {
      onConfigChange({ [field]: value })
    } else if (keys.length === 2) {
      onConfigChange({
        [keys[0]]: {
          ...config[keys[0] as keyof CertificateConfig],
          [keys[1]]: value,
        },
      })
    }
  }

  const handleColorChange = (colorKey: string, value: string) => {
    onConfigChange({
      colors: {
        ...config.colors,
        [colorKey]: value,
      },
    })
  }

  const handleLayoutChange = (layoutKey: string, value: any) => {
    onConfigChange({
      layout: {
        ...config.layout,
        [layoutKey]: value,
      },
    })
  }

  if (activeTab === "content") {
    return (
      <div className="space-y-6">
        <div>
          <h3 className="text-lg font-semibold mb-4">Company Information</h3>
          <div className="space-y-4">
            <div>
              <Label htmlFor="companyName">Company Name</Label>
              <Input
                id="companyName"
                value={config.companyName}
                onChange={(e) => handleInputChange("companyName", e.target.value)}
              />
            </div>
            <div>
              <Label htmlFor="companySubtitle">Company Subtitle</Label>
              <Input
                id="companySubtitle"
                value={config.companySubtitle}
                onChange={(e) => handleInputChange("companySubtitle", e.target.value)}
              />
            </div>
            <div>
              <Label htmlFor="companyId">Company ID</Label>
              <Input
                id="companyId"
                value={config.companyId}
                onChange={(e) => handleInputChange("companyId", e.target.value)}
              />
            </div>
          </div>
        </div>

        <Separator />

        <div>
          <h3 className="text-lg font-semibold mb-4">Contact Information</h3>
          <div className="grid grid-cols-2 gap-4">
            <div>
              <Label htmlFor="email">Email</Label>
              <Input
                id="email"
                value={config.contactInfo.email}
                onChange={(e) => handleInputChange("contactInfo.email", e.target.value)}
              />
            </div>
            <div>
              <Label htmlFor="website">Website</Label>
              <Input
                id="website"
                value={config.contactInfo.website}
                onChange={(e) => handleInputChange("contactInfo.website", e.target.value)}
              />
            </div>
            <div>
              <Label htmlFor="phone">Phone</Label>
              <Input
                id="phone"
                value={config.contactInfo.phone}
                onChange={(e) => handleInputChange("contactInfo.phone", e.target.value)}
              />
            </div>
            <div>
              <Label htmlFor="address">Address</Label>
              <Input
                id="address"
                value={config.contactInfo.address}
                onChange={(e) => handleInputChange("contactInfo.address", e.target.value)}
              />
            </div>
          </div>
        </div>

        <Separator />

        <div>
          <h3 className="text-lg font-semibold mb-4">Certificate Content</h3>
          <div className="space-y-4">
            <div>
              <Label htmlFor="certificateTitle">Certificate Title</Label>
              <Input
                id="certificateTitle"
                value={config.certificateTitle}
                onChange={(e) => handleInputChange("certificateTitle", e.target.value)}
              />
            </div>
            <div className="grid grid-cols-2 gap-4">
              <div>
                <Label htmlFor="studentName">Student Name</Label>
                <Input
                  id="studentName"
                  value={config.studentName}
                  onChange={(e) => handleInputChange("studentName", e.target.value)}
                />
              </div>
              <div>
                <Label htmlFor="courseName">Course Name</Label>
                <Input
                  id="courseName"
                  value={config.courseName}
                  onChange={(e) => handleInputChange("courseName", e.target.value)}
                />
              </div>
            </div>
            <div className="grid grid-cols-3 gap-4">
              <div>
                <Label htmlFor="courseHours">Course Hours</Label>
                <Input
                  id="courseHours"
                  value={config.courseHours}
                  onChange={(e) => handleInputChange("courseHours", e.target.value)}
                />
              </div>
              <div>
                <Label htmlFor="startDate">Start Date</Label>
                <Input
                  id="startDate"
                  type="date"
                  value={config.startDate}
                  onChange={(e) => handleInputChange("startDate", e.target.value)}
                />
              </div>
              <div>
                <Label htmlFor="endDate">End Date</Label>
                <Input
                  id="endDate"
                  type="date"
                  value={config.endDate}
                  onChange={(e) => handleInputChange("endDate", e.target.value)}
                />
              </div>
            </div>
            <div className="grid grid-cols-2 gap-4">
              <div>
                <Label htmlFor="instructorName">Instructor Name</Label>
                <Input
                  id="instructorName"
                  value={config.instructorName}
                  onChange={(e) => handleInputChange("instructorName", e.target.value)}
                />
              </div>
              <div>
                <Label htmlFor="instructorTitle">Instructor Title</Label>
                <Input
                  id="instructorTitle"
                  value={config.instructorTitle}
                  onChange={(e) => handleInputChange("instructorTitle", e.target.value)}
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    )
  }

  if (activeTab === "design") {
    return (
      <div className="space-y-6">
        <div>
          <h3 className="text-lg font-semibold mb-4">Color Scheme</h3>
          <div className="grid grid-cols-2 gap-4">
            <div>
              <Label htmlFor="primaryColor">Primary Color</Label>
              <div className="flex gap-2">
                <Input
                  id="primaryColor"
                  type="color"
                  value={config.colors.primary}
                  onChange={(e) => handleColorChange("primary", e.target.value)}
                  className="w-16 h-10 p-1"
                />
                <Input
                  value={config.colors.primary}
                  onChange={(e) => handleColorChange("primary", e.target.value)}
                  className="flex-1"
                />
              </div>
            </div>
            <div>
              <Label htmlFor="secondaryColor">Secondary Color</Label>
              <div className="flex gap-2">
                <Input
                  id="secondaryColor"
                  type="color"
                  value={config.colors.secondary}
                  onChange={(e) => handleColorChange("secondary", e.target.value)}
                  className="w-16 h-10 p-1"
                />
                <Input
                  value={config.colors.secondary}
                  onChange={(e) => handleColorChange("secondary", e.target.value)}
                  className="flex-1"
                />
              </div>
            </div>
            <div>
              <Label htmlFor="accentColor">Accent Color</Label>
              <div className="flex gap-2">
                <Input
                  id="accentColor"
                  type="color"
                  value={config.colors.accent}
                  onChange={(e) => handleColorChange("accent", e.target.value)}
                  className="w-16 h-10 p-1"
                />
                <Input
                  value={config.colors.accent}
                  onChange={(e) => handleColorChange("accent", e.target.value)}
                  className="flex-1"
                />
              </div>
            </div>
            <div>
              <Label htmlFor="textColor">Text Color</Label>
              <div className="flex gap-2">
                <Input
                  id="textColor"
                  type="color"
                  value={config.colors.text}
                  onChange={(e) => handleColorChange("text", e.target.value)}
                  className="w-16 h-10 p-1"
                />
                <Input
                  value={config.colors.text}
                  onChange={(e) => handleColorChange("text", e.target.value)}
                  className="flex-1"
                />
              </div>
            </div>
          </div>
        </div>

        <Separator />

        <div>
          <h3 className="text-lg font-semibold mb-4">Typography</h3>
          <div className="grid grid-cols-2 gap-4">
            <div>
              <Label htmlFor="headingFont">Heading Font</Label>
              <Select value={config.fonts.heading} onValueChange={(value) => handleInputChange("fonts.heading", value)}>
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="font-serif">Serif</SelectItem>
                  <SelectItem value="font-sans">Sans Serif</SelectItem>
                  <SelectItem value="font-mono">Monospace</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div>
              <Label htmlFor="bodyFont">Body Font</Label>
              <Select value={config.fonts.body} onValueChange={(value) => handleInputChange("fonts.body", value)}>
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="font-serif">Serif</SelectItem>
                  <SelectItem value="font-sans">Sans Serif</SelectItem>
                  <SelectItem value="font-mono">Monospace</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>
        </div>
      </div>
    )
  }

  if (activeTab === "layout") {
    return (
      <div className="space-y-6">
        <div>
          <h3 className="text-lg font-semibold mb-4">Border Style</h3>
          <Select value={config.layout.borderStyle} onValueChange={(value) => handleLayoutChange("borderStyle", value)}>
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="geometric">Geometric</SelectItem>
              <SelectItem value="classic">Classic</SelectItem>
              <SelectItem value="modern">Modern</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div>
          <h3 className="text-lg font-semibold mb-4">QR Code Position</h3>
          <Select value={config.layout.qrPosition} onValueChange={(value) => handleLayoutChange("qrPosition", value)}>
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="left">Bottom Left</SelectItem>
              <SelectItem value="right">Bottom Right</SelectItem>
              <SelectItem value="center">Bottom Center</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div className="flex items-center space-x-2">
          <Switch
            id="showLogos"
            checked={config.layout.showLogos}
            onCheckedChange={(checked) => handleLayoutChange("showLogos", checked)}
          />
          <Label htmlFor="showLogos">Show Company Logos</Label>
        </div>
      </div>
    )
  }

  return null
}
